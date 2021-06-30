<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210629154744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD id_cinema_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495534FE3891 FOREIGN KEY (id_cinema_id) REFERENCES cinema (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42C8495534FE3891 ON reservation (id_cinema_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495534FE3891');
        $this->addSql('DROP INDEX UNIQ_42C8495534FE3891 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP id_cinema_id');
    }
}
