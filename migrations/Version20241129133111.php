<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129133111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__duck AS SELECT id, firstname, lastname, duckname, email, password FROM duck');
        $this->addSql('DROP TABLE duck');
        $this->addSql('CREATE TABLE duck (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(45) NOT NULL, lastname VARCHAR(45) NOT NULL, duckname VARCHAR(45) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO duck (id, firstname, lastname, duckname, email, password) SELECT id, firstname, lastname, duckname, email, password FROM __temp__duck');
        $this->addSql('DROP TABLE __temp__duck');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON duck (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__duck AS SELECT id, email, password, firstname, lastname, duckname FROM duck');
        $this->addSql('DROP TABLE duck');
        $this->addSql('CREATE TABLE duck (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(100) NOT NULL, firstname VARCHAR(45) NOT NULL, lastname VARCHAR(45) NOT NULL, duckname VARCHAR(45) NOT NULL)');
        $this->addSql('INSERT INTO duck (id, email, password, firstname, lastname, duckname) SELECT id, email, password, firstname, lastname, duckname FROM __temp__duck');
        $this->addSql('DROP TABLE __temp__duck');
    }
}
