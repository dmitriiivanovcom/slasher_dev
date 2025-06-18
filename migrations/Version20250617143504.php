<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617143504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE monster (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, portrait VARCHAR(255) DEFAULT NULL, background_image VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, strengths VARCHAR(255) DEFAULT NULL, weaknesses VARCHAR(255) DEFAULT NULL, lethality INTEGER NOT NULL, speed INTEGER NOT NULL, stealth INTEGER NOT NULL, name VARCHAR(255) NOT NULL, rank VARCHAR(255) NOT NULL, danger_index INTEGER NOT NULL, backstory CLOB DEFAULT NULL)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE monster
        SQL);
    }
}
