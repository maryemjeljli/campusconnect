<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241218075547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE postuler (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, formation VARCHAR(255) NOT NULL, experiences_academiques LONGTEXT NOT NULL, competences LONGTEXT NOT NULL, lettre_motivation LONGTEXT NOT NULL, phone VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage (id INT AUTO_INCREMENT NOT NULL, typestage_id INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, domaine VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, entreprise VARCHAR(255) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, datededebut DATE DEFAULT NULL, datedefin DATE DEFAULT NULL, INDEX IDX_C27C93697EF15F1B (typestage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typestage (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C93697EF15F1B FOREIGN KEY (typestage_id) REFERENCES typestage (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C93697EF15F1B');
        $this->addSql('DROP TABLE postuler');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE typestage');
    }
}
