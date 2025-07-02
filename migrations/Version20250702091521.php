<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250702091521 extends AbstractMigration
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
            CREATE TABLE character (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, strength INTEGER NOT NULL, agility INTEGER NOT NULL, intelligence INTEGER NOT NULL, charisma INTEGER NOT NULL, quote VARCHAR(300) DEFAULT NULL, role VARCHAR(100) DEFAULT NULL, portrait VARCHAR(255) DEFAULT NULL, background_image VARCHAR(255) DEFAULT NULL, motto VARCHAR(255) DEFAULT NULL, weaknesses VARCHAR(255) DEFAULT NULL, strengths VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_937AB034F675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO character (id, name, strength, agility, intelligence, charisma, quote, role, portrait, background_image, motto, weaknesses, strengths, description) SELECT id, name, strength, agility, intelligence, charisma, quote, role, portrait, background_image, motto, weaknesses, strengths, description FROM __temp__character
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__character
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_937AB034F675F31B ON character (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__location AS SELECT id, name, description, image, map FROM location
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE location
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE location (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, image VARCHAR(255) NOT NULL, map VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_5E9E89CBF675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO location (id, name, description, image, map) SELECT id, name, description, image, map FROM __temp__location
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__location
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5E9E89CBF675F31B ON location (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__monster AS SELECT id, portrait, background_image, title, description, strengths, weaknesses, lethality, speed, stealth, name, rank, danger_index, backstory FROM monster
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE monster
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE monster (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER DEFAULT NULL, portrait VARCHAR(255) DEFAULT NULL, background_image VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, strengths VARCHAR(255) DEFAULT NULL, weaknesses VARCHAR(255) DEFAULT NULL, lethality INTEGER NOT NULL, speed INTEGER NOT NULL, stealth INTEGER NOT NULL, name VARCHAR(255) NOT NULL, rank VARCHAR(255) NOT NULL, danger_index INTEGER NOT NULL, backstory CLOB DEFAULT NULL, CONSTRAINT FK_245EC6F4F675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO monster (id, portrait, background_image, title, description, strengths, weaknesses, lethality, speed, stealth, name, rank, danger_index, backstory) SELECT id, portrait, background_image, title, description, strengths, weaknesses, lethality, speed, stealth, name, rank, danger_index, backstory FROM __temp__monster
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__monster
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_245EC6F4F675F31B ON monster (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__weapon AS SELECT id, name, title, description, type, image, ammo_status, damage, range FROM weapon
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE weapon
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE weapon (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, ammo_status VARCHAR(255) DEFAULT NULL, damage INTEGER DEFAULT NULL, range VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_6933A7E6F675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO weapon (id, name, title, description, type, image, ammo_status, damage, range) SELECT id, name, title, description, type, image, ammo_status, damage, range FROM __temp__weapon
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__weapon
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6933A7E6F675F31B ON weapon (author_id)
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
            CREATE TABLE character (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, strength INTEGER NOT NULL, agility INTEGER NOT NULL, intelligence INTEGER NOT NULL, charisma INTEGER NOT NULL, quote VARCHAR(300) DEFAULT NULL, role VARCHAR(100) DEFAULT NULL, portrait VARCHAR(255) DEFAULT NULL, background_image VARCHAR(255) DEFAULT NULL, motto VARCHAR(255) DEFAULT NULL, weaknesses VARCHAR(255) DEFAULT NULL, strengths VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO character (id, name, strength, agility, intelligence, charisma, quote, role, portrait, background_image, motto, weaknesses, strengths, description) SELECT id, name, strength, agility, intelligence, charisma, quote, role, portrait, background_image, motto, weaknesses, strengths, description FROM __temp__character
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__character
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__location AS SELECT id, name, description, image, map FROM location
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE location
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE location (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, image VARCHAR(255) NOT NULL, map VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO location (id, name, description, image, map) SELECT id, name, description, image, map FROM __temp__location
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__location
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__monster AS SELECT id, portrait, background_image, title, description, strengths, weaknesses, lethality, speed, stealth, name, rank, danger_index, backstory FROM monster
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE monster
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE monster (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, portrait VARCHAR(255) DEFAULT NULL, background_image VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, strengths VARCHAR(255) DEFAULT NULL, weaknesses VARCHAR(255) DEFAULT NULL, lethality INTEGER NOT NULL, speed INTEGER NOT NULL, stealth INTEGER NOT NULL, name VARCHAR(255) NOT NULL, rank VARCHAR(255) NOT NULL, danger_index INTEGER NOT NULL, backstory CLOB DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO monster (id, portrait, background_image, title, description, strengths, weaknesses, lethality, speed, stealth, name, rank, danger_index, backstory) SELECT id, portrait, background_image, title, description, strengths, weaknesses, lethality, speed, stealth, name, rank, danger_index, backstory FROM __temp__monster
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__monster
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__weapon AS SELECT id, name, title, description, type, image, ammo_status, damage, range FROM weapon
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE weapon
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE weapon (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, ammo_status VARCHAR(255) DEFAULT NULL, damage INTEGER DEFAULT NULL, range VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO weapon (id, name, title, description, type, image, ammo_status, damage, range) SELECT id, name, title, description, type, image, ammo_status, damage, range FROM __temp__weapon
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__weapon
        SQL);
    }
}
