<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210630184204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE film ADD id_cinema_id INT NOT NULL');
        $this->addSql('ALTER TABLE film ADD CONSTRAINT FK_8244BE2234FE3891 FOREIGN KEY (id_cinema_id) REFERENCES cinema (id)');
        $this->addSql('CREATE INDEX IDX_8244BE2234FE3891 ON film (id_cinema_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE film DROP FOREIGN KEY FK_8244BE2234FE3891');
        $this->addSql('DROP INDEX IDX_8244BE2234FE3891 ON film');
        $this->addSql('ALTER TABLE film DROP id_cinema_id');
    }
}
