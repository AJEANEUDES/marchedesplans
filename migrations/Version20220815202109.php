<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220815202109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat (id INT AUTO_INCREMENT NOT NULL, plan_id INT NOT NULL, users_id INT NOT NULL, miniplan_id INT NOT NULL, panier_id INT DEFAULT NULL, etat VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', payement VARCHAR(255) NOT NULL, txreference INT DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, retrait TINYINT(1) DEFAULT NULL, demande VARCHAR(50) DEFAULT NULL, statusmail TINYINT(1) DEFAULT NULL, INDEX IDX_26A98456E899029B (plan_id), INDEX IDX_26A9845667B3B43D (users_id), INDEX IDX_26A98456F3C5792A (miniplan_id), INDEX IDX_26A98456F77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE achats (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, adress VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE architecte (id INT AUTO_INCREMENT NOT NULL, num_banque VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, tel VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consulter (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, plans_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_69A83B75A76ED395 (user_id), INDEX IDX_69A83B7580446EEB (plans_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fichiers (id INT AUTO_INCREMENT NOT NULL, miniplans_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_969DB4AB3C39B18A (miniplans_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forme (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9EBAEA6A4D60759 (libelle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, plans_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6A80446EEB (plans_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, recipient_id INT NOT NULL, contenu VARCHAR(255) DEFAULT NULL, titre VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_read TINYINT(1) NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FE92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE miniplans (id INT AUTO_INCREMENT NOT NULL, plans_id INT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, tx_reference VARCHAR(255) DEFAULT NULL, vente INT DEFAULT NULL, INDEX IDX_83B6CF2B80446EEB (plans_id), INDEX IDX_83B6CF2BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, total DOUBLE PRECISION NOT NULL, code INT NOT NULL, txreference INT NOT NULL, etat TINYINT(1) NOT NULL, payement VARCHAR(25) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', statusmail TINYINT(1) DEFAULT NULL, INDEX IDX_24CC0DF2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, code INT NOT NULL, alpha2 VARCHAR(255) NOT NULL, alpha3 VARCHAR(255) NOT NULL, nom_en_gb VARCHAR(255) NOT NULL, nom_fr_fr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE superficie (id INT AUTO_INCREMENT NOT NULL, nombredelots VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E4DE844B6A7E2569 (nombredelots), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tx_reference (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8CDE5729A4D60759 (libelle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, pays_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, prenoms VARCHAR(255) NOT NULL, activation_token VARCHAR(70) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, banque VARCHAR(255) DEFAULT NULL, tel INT DEFAULT NULL, is_verified TINYINT(1) DEFAULT NULL, roles VARCHAR(15) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E9A6E44244 (pays_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, registered_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), UNIQUE INDEX UNIQ_1D1C63B386CC499D (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE withdrawal (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, commission DOUBLE PRECISION NOT NULL, reste DOUBLE PRECISION NOT NULL, tel INT NOT NULL, etat VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6D2D3B45A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456E899029B FOREIGN KEY (plan_id) REFERENCES plans (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A9845667B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456F3C5792A FOREIGN KEY (miniplan_id) REFERENCES miniplans (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE consulter ADD CONSTRAINT FK_69A83B75A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE consulter ADD CONSTRAINT FK_69A83B7580446EEB FOREIGN KEY (plans_id) REFERENCES plans (id)');
        $this->addSql('ALTER TABLE fichiers ADD CONSTRAINT FK_969DB4AB3C39B18A FOREIGN KEY (miniplans_id) REFERENCES miniplans (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A80446EEB FOREIGN KEY (plans_id) REFERENCES plans (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE92F8F78 FOREIGN KEY (recipient_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE miniplans ADD CONSTRAINT FK_83B6CF2B80446EEB FOREIGN KEY (plans_id) REFERENCES plans (id)');
        $this->addSql('ALTER TABLE miniplans ADD CONSTRAINT FK_83B6CF2BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE withdrawal ADD CONSTRAINT FK_6D2D3B45A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE plans ADD CONSTRAINT FK_356798D1C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE plans ADD CONSTRAINT FK_356798D1A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE plans ADD CONSTRAINT FK_356798D1BCE84E7C FOREIGN KEY (forme_id) REFERENCES forme (id)');
        $this->addSql('ALTER TABLE plans ADD CONSTRAINT FK_356798D1240A0569 FOREIGN KEY (superficie_id) REFERENCES superficie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plans DROP FOREIGN KEY FK_356798D1BCE84E7C');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456F3C5792A');
        $this->addSql('ALTER TABLE fichiers DROP FOREIGN KEY FK_969DB4AB3C39B18A');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456F77D927C');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9A6E44244');
        $this->addSql('ALTER TABLE plans DROP FOREIGN KEY FK_356798D1240A0569');
        $this->addSql('ALTER TABLE plans DROP FOREIGN KEY FK_356798D1C54C8C93');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A9845667B3B43D');
        $this->addSql('ALTER TABLE consulter DROP FOREIGN KEY FK_69A83B75A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE92F8F78');
        $this->addSql('ALTER TABLE miniplans DROP FOREIGN KEY FK_83B6CF2BA76ED395');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2A76ED395');
        $this->addSql('ALTER TABLE plans DROP FOREIGN KEY FK_356798D1A76ED395');
        $this->addSql('ALTER TABLE withdrawal DROP FOREIGN KEY FK_6D2D3B45A76ED395');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE achats');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE architecte');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE consulter');
        $this->addSql('DROP TABLE fichiers');
        $this->addSql('DROP TABLE forme');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE miniplans');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE superficie');
        $this->addSql('DROP TABLE tx_reference');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE withdrawal');
    }
}
