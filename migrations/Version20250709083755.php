<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250709083755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE weapon ADD COLUMN weight INTEGER DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__weapon AS SELECT id, author_id, name, title, description, type, image, ammo_status, damage, range FROM weapon
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE weapon
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE weapon (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, ammo_status VARCHAR(255) DEFAULT NULL, damage INTEGER DEFAULT NULL, range VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_6933A7E6F675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO weapon (id, author_id, name, title, description, type, image, ammo_status, damage, range) SELECT id, author_id, name, title, description, type, image, ammo_status, damage, range FROM __temp__weapon
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__weapon
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6933A7E6F675F31B ON weapon (author_id)
        SQL);
    }
}
