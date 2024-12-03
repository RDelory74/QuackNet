<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241203105841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__coincoin AS SELECT id, author_id, message, picture, created_time FROM coincoin');
        $this->addSql('DROP TABLE coincoin');
        $this->addSql('CREATE TABLE coincoin (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, parent_id_id INTEGER NOT NULL, message CLOB NOT NULL, picture VARCHAR(255) NOT NULL, created_time DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_3B6C9BC2F675F31B FOREIGN KEY (author_id) REFERENCES duck (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3B6C9BC2B3750AF4 FOREIGN KEY (parent_id_id) REFERENCES quack (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO coincoin (id, author_id, message, picture, created_time) SELECT id, author_id, message, picture, created_time FROM __temp__coincoin');
        $this->addSql('DROP TABLE __temp__coincoin');
        $this->addSql('CREATE INDEX IDX_3B6C9BC2F675F31B ON coincoin (author_id)');
        $this->addSql('CREATE INDEX IDX_3B6C9BC2B3750AF4 ON coincoin (parent_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__coincoin AS SELECT id, author_id, message, picture, created_time FROM coincoin');
        $this->addSql('DROP TABLE coincoin');
        $this->addSql('CREATE TABLE coincoin (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, message CLOB NOT NULL, picture VARCHAR(255) NOT NULL, created_time DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , tags CLOB NOT NULL --(DC2Type:array)
        , CONSTRAINT FK_3B6C9BC2F675F31B FOREIGN KEY (author_id) REFERENCES duck (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO coincoin (id, author_id, message, picture, created_time) SELECT id, author_id, message, picture, created_time FROM __temp__coincoin');
        $this->addSql('DROP TABLE __temp__coincoin');
        $this->addSql('CREATE INDEX IDX_3B6C9BC2F675F31B ON coincoin (author_id)');
    }
}
