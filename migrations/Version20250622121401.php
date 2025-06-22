<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622121401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__character AS SELECT id, name, strength, agility, intelligence, charisma, quote, role, portrait, background_image, motto, weaknesses, strengths, description FROM character
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE character
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE character (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, strength INTEGER NOT NULL, agility INTEGER NOT NULL, intelligence INTEGER NOT NULL, charisma INTEGER NOT NULL, quote VARCHAR(300) DEFAULT NULL, role VARCHAR(100) DEFAULT NULL, portrait VARCHAR(255) DEFAULT NULL, background_image VARCHAR(255) DEFAULT NULL, motto VARCHAR(255) DEFAULT NULL, weaknesses VARCHAR(255) DEFAULT NULL, strengths VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO character (id, name, strength, agility, intelligence, charisma, quote, role, portrait, background_image, motto, weaknesses, strengths, description) SELECT id, name, strength, agility, intelligence, charisma, quote, role, portrait, background_image, motto, weaknesses, strengths, description FROM __temp__character
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__character
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__character AS SELECT id, name, strength, agility, intelligence, charisma, quote, role, portrait, background_image, motto, weaknesses, strengths, description FROM character
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE character
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE character (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, strength INTEGER NOT NULL, agility INTEGER NOT NULL, intelligence INTEGER NOT NULL, charisma INTEGER NOT NULL, quote VARCHAR(300) NOT NULL, role VARCHAR(100) NOT NULL, portrait VARCHAR(255) DEFAULT NULL, background_image VARCHAR(255) DEFAULT NULL, motto VARCHAR(255) DEFAULT NULL, weaknesses VARCHAR(255) DEFAULT NULL, strengths VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO character (id, name, strength, agility, intelligence, charisma, quote, role, portrait, background_image, motto, weaknesses, strengths, description) SELECT id, name, strength, agility, intelligence, charisma, quote, role, portrait, background_image, motto, weaknesses, strengths, description FROM __temp__character
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__character
        SQL);
    }
}
