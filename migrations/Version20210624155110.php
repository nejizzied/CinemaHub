<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624155110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD id_user_id INT NOT NULL, ADD id_film_id INT NOT NULL, ADD id_admin_id INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC88E2F8F3 FOREIGN KEY (id_film_id) REFERENCES film (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC34F06E85 FOREIGN KEY (id_admin_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_67F068BC79F37AE5 ON commentaire (id_user_id)');
        $this->addSql('CREATE INDEX IDX_67F068BC88E2F8F3 ON commentaire (id_film_id)');
        $this->addSql('CREATE INDEX IDX_67F068BC34F06E85 ON commentaire (id_admin_id)');
        $this->addSql('ALTER TABLE film ADD id_categorie_id INT NOT NULL, ADD id_admin_id INT NOT NULL');
        $this->addSql('ALTER TABLE film ADD CONSTRAINT FK_8244BE229F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE film ADD CONSTRAINT FK_8244BE2234F06E85 FOREIGN KEY (id_admin_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_8244BE229F34925F ON film (id_categorie_id)');
        $this->addSql('CREATE INDEX IDX_8244BE2234F06E85 ON film (id_admin_id)');
        $this->addSql('ALTER TABLE publicite ADD id_cinema_id INT NOT NULL, ADD id_admin_id INT NOT NULL');
        $this->addSql('ALTER TABLE publicite ADD CONSTRAINT FK_1D394E3934FE3891 FOREIGN KEY (id_cinema_id) REFERENCES cinema (id)');
        $this->addSql('ALTER TABLE publicite ADD CONSTRAINT FK_1D394E3934F06E85 FOREIGN KEY (id_admin_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_1D394E3934FE3891 ON publicite (id_cinema_id)');
        $this->addSql('CREATE INDEX IDX_1D394E3934F06E85 ON publicite (id_admin_id)');
        $this->addSql('ALTER TABLE reservation ADD id_film_id INT NOT NULL, ADD id_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495588E2F8F3 FOREIGN KEY (id_film_id) REFERENCES film (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_42C8495588E2F8F3 ON reservation (id_film_id)');
        $this->addSql('CREATE INDEX IDX_42C8495579F37AE5 ON reservation (id_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC34F06E85');
        $this->addSql('ALTER TABLE film DROP FOREIGN KEY FK_8244BE2234F06E85');
        $this->addSql('ALTER TABLE publicite DROP FOREIGN KEY FK_1D394E3934F06E85');
        $this->addSql('DROP TABLE admin');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC79F37AE5');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC88E2F8F3');
        $this->addSql('DROP INDEX IDX_67F068BC79F37AE5 ON commentaire');
        $this->addSql('DROP INDEX IDX_67F068BC88E2F8F3 ON commentaire');
        $this->addSql('DROP INDEX IDX_67F068BC34F06E85 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP id_user_id, DROP id_film_id, DROP id_admin_id');
        $this->addSql('ALTER TABLE film DROP FOREIGN KEY FK_8244BE229F34925F');
        $this->addSql('DROP INDEX IDX_8244BE229F34925F ON film');
        $this->addSql('DROP INDEX IDX_8244BE2234F06E85 ON film');
        $this->addSql('ALTER TABLE film DROP id_categorie_id, DROP id_admin_id');
        $this->addSql('ALTER TABLE publicite DROP FOREIGN KEY FK_1D394E3934FE3891');
        $this->addSql('DROP INDEX IDX_1D394E3934FE3891 ON publicite');
        $this->addSql('DROP INDEX IDX_1D394E3934F06E85 ON publicite');
        $this->addSql('ALTER TABLE publicite DROP id_cinema_id, DROP id_admin_id');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495588E2F8F3');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495579F37AE5');
        $this->addSql('DROP INDEX IDX_42C8495588E2F8F3 ON reservation');
        $this->addSql('DROP INDEX IDX_42C8495579F37AE5 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP id_film_id, DROP id_user_id');
    }
}
