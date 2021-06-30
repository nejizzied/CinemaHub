<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210630183818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE film_cinema');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE film_cinema (film_id INT NOT NULL, cinema_id INT NOT NULL, INDEX IDX_BF7139BE567F5183 (film_id), INDEX IDX_BF7139BEB4CB84B6 (cinema_id), PRIMARY KEY(film_id, cinema_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE film_cinema ADD CONSTRAINT FK_BF7139BE567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_cinema ADD CONSTRAINT FK_BF7139BEB4CB84B6 FOREIGN KEY (cinema_id) REFERENCES cinema (id) ON DELETE CASCADE');
    }
}
