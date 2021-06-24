<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624150703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salle_de_projection ADD id_cinema_id INT NOT NULL');
        $this->addSql('ALTER TABLE salle_de_projection ADD CONSTRAINT FK_87C2573034FE3891 FOREIGN KEY (id_cinema_id) REFERENCES cinema (id)');
        $this->addSql('CREATE INDEX IDX_87C2573034FE3891 ON salle_de_projection (id_cinema_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salle_de_projection DROP FOREIGN KEY FK_87C2573034FE3891');
        $this->addSql('DROP INDEX IDX_87C2573034FE3891 ON salle_de_projection');
        $this->addSql('ALTER TABLE salle_de_projection DROP id_cinema_id');
    }
}
