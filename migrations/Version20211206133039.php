<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211206133039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE link (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__test AS SELECT id, name, age FROM test');
        $this->addSql('DROP TABLE test');
        $this->addSql('CREATE TABLE test (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, link_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, age INTEGER NOT NULL, CONSTRAINT FK_D87F7E0CADA40271 FOREIGN KEY (link_id) REFERENCES link (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO test (id, name, age) SELECT id, name, age FROM __temp__test');
        $this->addSql('DROP TABLE __temp__test');
        $this->addSql('CREATE INDEX IDX_D87F7E0CADA40271 ON test (link_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE link');
        $this->addSql('DROP INDEX IDX_D87F7E0CADA40271');
        $this->addSql('CREATE TEMPORARY TABLE __temp__test AS SELECT id, name, age FROM test');
        $this->addSql('DROP TABLE test');
        $this->addSql('CREATE TABLE test (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, age INTEGER NOT NULL)');
        $this->addSql('INSERT INTO test (id, name, age) SELECT id, name, age FROM __temp__test');
        $this->addSql('DROP TABLE __temp__test');
    }
}
