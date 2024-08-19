<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716100432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE individu DROP FOREIGN KEY FK_5EE42FCE5F3A750A');
        $this->addSql('DROP INDEX IDX_5EE42FCE5F3A750A ON individu');
        $this->addSql('ALTER TABLE individu CHANGE log_id_id logement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE individu ADD CONSTRAINT FK_5EE42FCE58ABF955 FOREIGN KEY (logement_id) REFERENCES logement (id)');
        $this->addSql('CREATE INDEX IDX_5EE42FCE58ABF955 ON individu (logement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE individu DROP FOREIGN KEY FK_5EE42FCE58ABF955');
        $this->addSql('DROP INDEX IDX_5EE42FCE58ABF955 ON individu');
        $this->addSql('ALTER TABLE individu CHANGE logement_id log_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE individu ADD CONSTRAINT FK_5EE42FCE5F3A750A FOREIGN KEY (log_id_id) REFERENCES logement (id)');
        $this->addSql('CREATE INDEX IDX_5EE42FCE5F3A750A ON individu (log_id_id)');
    }
}
