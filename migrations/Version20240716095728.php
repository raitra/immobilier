<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716095728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logement ADD quartier_id INT DEFAULT NULL, ADD type_log_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD4457DF1E57AB FOREIGN KEY (quartier_id) REFERENCES quartier (id)');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD4457C9B036BE FOREIGN KEY (type_log_id) REFERENCES type_logement (id)');
        $this->addSql('CREATE INDEX IDX_F0FD4457DF1E57AB ON logement (quartier_id)');
        $this->addSql('CREATE INDEX IDX_F0FD4457C9B036BE ON logement (type_log_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD4457DF1E57AB');
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD4457C9B036BE');
        $this->addSql('DROP INDEX IDX_F0FD4457DF1E57AB ON logement');
        $this->addSql('DROP INDEX IDX_F0FD4457C9B036BE ON logement');
        $this->addSql('ALTER TABLE logement DROP quartier_id, DROP type_log_id');
    }
}
