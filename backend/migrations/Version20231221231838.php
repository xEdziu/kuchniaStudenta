<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231221231838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, calories_per_100gram DOUBLE PRECISION NOT NULL, protein_per_100gram DOUBLE PRECISION NOT NULL, carbohydrates_per_100gram DOUBLE PRECISION NOT NULL, fat_per_100gram DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION NOT NULL, measure_type VARCHAR(255) NOT NULL, quantity DOUBLE PRECISION NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient_store (ingredient_id INT NOT NULL, store_id INT NOT NULL, INDEX IDX_E6653FB6933FE08C (ingredient_id), INDEX IDX_E6653FB6B092A811 (store_id), PRIMARY KEY(ingredient_id, store_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, instructions JSON NOT NULL COMMENT \'(DC2Type:json)\', rating DOUBLE PRECISION NOT NULL, rating_people_number DOUBLE PRECISION DEFAULT NULL, label VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ingridients JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_DA88B1379D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, hash VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredient_store ADD CONSTRAINT FK_E6653FB6933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_store ADD CONSTRAINT FK_E6653FB6B092A811 FOREIGN KEY (store_id) REFERENCES store (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B1379D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient_store DROP FOREIGN KEY FK_E6653FB6933FE08C');
        $this->addSql('ALTER TABLE ingredient_store DROP FOREIGN KEY FK_E6653FB6B092A811');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B1379D86650F');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE ingredient_store');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE store');
        $this->addSql('DROP TABLE user');
    }
}
