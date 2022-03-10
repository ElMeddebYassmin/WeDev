<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310143110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, code_id INT DEFAULT NULL, adresse_livraison VARCHAR(255) DEFAULT NULL, total_commande DOUBLE PRECISION NOT NULL, mode_livraison VARCHAR(255) NOT NULL, renseignement VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_6EEAA67D27DAFE17 (code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, code INT NOT NULL, date_limite DATE DEFAULT NULL, UNIQUE INDEX UNIQ_64BF3F0277153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, nom_produit VARCHAR(255) NOT NULL, description_produit LONGTEXT NOT NULL, image_produit LONGBLOB NOT NULL, created_at DATETIME NOT NULL, prix_produit INT NOT NULL, nomimage_produit VARCHAR(255) NOT NULL, INDEX IDX_BE2DDF8CB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom_restaurant VARCHAR(255) NOT NULL, image_restaurant LONGBLOB NOT NULL, nom_image VARCHAR(255) NOT NULL, categorie_restaurant VARCHAR(255) NOT NULL, addresse VARCHAR(255) NOT NULL, cite VARCHAR(255) NOT NULL, code_postal INT NOT NULL, created_at DATETIME NOT NULL, heure_ouverture TIME NOT NULL, heure_fermeture TIME NOT NULL, status VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_EB95123FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D27DAFE17 FOREIGN KEY (code_id) REFERENCES coupon (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D27DAFE17');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CB1E7706E');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123FA76ED395');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE `user`');
    }
}
