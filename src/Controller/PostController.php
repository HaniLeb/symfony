<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepo): Response
    {
        $arrayPosts = $postRepo->findAll();

        // dd($arrayPosts);

        return $this->render('post/index.html.twig', ['posts' => $arrayPosts]);
    }

    
    #[Route('/post/{id<\d+>}', name: 'app_post_details', methods: ['GET'])]
    public function details(Post $post): Response
    {
        dd($post);
        
        return $this->render('post/index.html.twig', []);
    }

    #[Route('/post/create', name: 'app_post_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post();
        $formulaire = $this->createForm(PostType::class, $post);
        $formulaire->handleRequest($request);
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $em->persist($post); // Met en cache les opérations  d'insert/update d'un onjet pour effectuer une transaction 
            $em->flush(); // Execute toutes les opérations mises en cache (transaction SQL)

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('post/create.html.twig', ['form' => $formulaire, 'action' => 'Créer']);
    }

    #[Route('/post/edit/{id<\d+>}', name: 'app_post_edit')]
    public function edit(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $formulaire = $this->createForm(PostType::class, $post);
        $formulaire->handleRequest($request);
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('post/create.html.twig', ['form' => $formulaire, 'action' => 'Mettre à jour']);
    }

    #[Route('/post/{id<\d+>}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token')))
        {
            $em->remove($post);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }
        return $this->render('post/delete.html.twig');
    }
}