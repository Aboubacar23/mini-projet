<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230917141304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emprunt (id INT AUTO_INCREMENT NOT NULL, personne_id INT DEFAULT NULL, objet_id INT DEFAULT NULL, date_debut DATE NOT NULL, date_retour DATE DEFAULT NULL, INDEX IDX_364071D7A21BD112 (personne_id), INDEX IDX_364071D7F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7F520CF5A FOREIGN KEY (objet_id) REFERENCES objet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7A21BD112');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7F520CF5A');
        $this->addSql('DROP TABLE emprunt');
    }
}
