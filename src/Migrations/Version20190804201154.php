<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190804201154 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom_complet VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, nci INT NOT NULL, tel INT NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE versement (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT NOT NULL, caissier_id INT DEFAULT NULL, versement_user_id INT DEFAULT NULL, numero_compte INT NOT NULL, solde INT NOT NULL, date_versement DATE NOT NULL, INDEX IDX_716E9367A4AEAFEA (entreprise_id), INDEX IDX_716E9367B514973B (caissier_id), INDEX IDX_716E9367216450BE (versement_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, linea VARCHAR(255) NOT NULL, raison_social VARCHAR(255) NOT NULL, solde INT DEFAULT NULL, nom_complet VARCHAR(255) NOT NULL, nci INT NOT NULL, tel INT NOT NULL, adresse VARCHAR(255) NOT NULL, numero_compte INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E9367A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E9367B514973B FOREIGN KEY (caissier_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E9367216450BE FOREIGN KEY (versement_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E9367B514973B');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E9367216450BE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A4AEAFEA');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E9367A4AEAFEA');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE versement');
        $this->addSql('DROP TABLE entreprise');
    }
}
