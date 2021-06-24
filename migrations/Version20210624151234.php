<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624151234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salle_de_projection ADD id_film_id INT NOT NULL');
        $this->addSql('ALTER TABLE salle_de_projection ADD CONSTRAINT FK_87C2573088E2F8F3 FOREIGN KEY (id_film_id) REFERENCES film (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_87C2573088E2F8F3 ON salle_de_projection (id_film_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salle_de_projection DROP FOREIGN KEY FK_87C2573088E2F8F3');
        $this->addSql('DROP INDEX UNIQ_87C2573088E2F8F3 ON salle_de_projection');
        $this->addSql('ALTER TABLE salle_de_projection DROP id_film_id');
    }
}
