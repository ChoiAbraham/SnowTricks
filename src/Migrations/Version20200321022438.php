<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200321022438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment_entity (id INT AUTO_INCREMENT NOT NULL, trick_id INT NOT NULL, user_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C43B1C7AB281BE2E (trick_id), INDEX IDX_C43B1C7AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_trick (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trick_entity (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, last_user_id INT NOT NULL, group_trick_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, slug VARCHAR(100) NOT NULL, INDEX IDX_553DC57561220EA6 (creator_id), INDEX IDX_553DC575C1E2EFDD (last_user_id), INDEX IDX_553DC575BBB1F251 (group_trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trick_image_entity (id INT AUTO_INCREMENT NOT NULL, trick_id INT NOT NULL, image_file_name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, first_image TINYINT(1) NOT NULL, alt_image VARCHAR(100) NOT NULL, INDEX IDX_95D5F43BB281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trick_video (id INT AUTO_INCREMENT NOT NULL, trick_id INT NOT NULL, name VARCHAR(45) DEFAULT NULL, path_url VARCHAR(255) NOT NULL, INDEX IDX_B7E8DA93B281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, token VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, picture_path VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_entity ADD CONSTRAINT FK_C43B1C7AB281BE2E FOREIGN KEY (trick_id) REFERENCES trick_entity (id)');
        $this->addSql('ALTER TABLE comment_entity ADD CONSTRAINT FK_C43B1C7AA76ED395 FOREIGN KEY (user_id) REFERENCES user_entity (id)');
        $this->addSql('ALTER TABLE trick_entity ADD CONSTRAINT FK_553DC57561220EA6 FOREIGN KEY (creator_id) REFERENCES user_entity (id)');
        $this->addSql('ALTER TABLE trick_entity ADD CONSTRAINT FK_553DC575C1E2EFDD FOREIGN KEY (last_user_id) REFERENCES user_entity (id)');
        $this->addSql('ALTER TABLE trick_entity ADD CONSTRAINT FK_553DC575BBB1F251 FOREIGN KEY (group_trick_id) REFERENCES group_trick (id)');
        $this->addSql('ALTER TABLE trick_image_entity ADD CONSTRAINT FK_95D5F43BB281BE2E FOREIGN KEY (trick_id) REFERENCES trick_entity (id)');
        $this->addSql('ALTER TABLE trick_video ADD CONSTRAINT FK_B7E8DA93B281BE2E FOREIGN KEY (trick_id) REFERENCES trick_entity (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trick_entity DROP FOREIGN KEY FK_553DC575BBB1F251');
        $this->addSql('ALTER TABLE comment_entity DROP FOREIGN KEY FK_C43B1C7AB281BE2E');
        $this->addSql('ALTER TABLE trick_image_entity DROP FOREIGN KEY FK_95D5F43BB281BE2E');
        $this->addSql('ALTER TABLE trick_video DROP FOREIGN KEY FK_B7E8DA93B281BE2E');
        $this->addSql('ALTER TABLE comment_entity DROP FOREIGN KEY FK_C43B1C7AA76ED395');
        $this->addSql('ALTER TABLE trick_entity DROP FOREIGN KEY FK_553DC57561220EA6');
        $this->addSql('ALTER TABLE trick_entity DROP FOREIGN KEY FK_553DC575C1E2EFDD');
        $this->addSql('DROP TABLE comment_entity');
        $this->addSql('DROP TABLE group_trick');
        $this->addSql('DROP TABLE trick_entity');
        $this->addSql('DROP TABLE trick_image_entity');
        $this->addSql('DROP TABLE trick_video');
        $this->addSql('DROP TABLE user_entity');
    }
}
