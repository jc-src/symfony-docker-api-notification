<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250902140546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(180) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, contact_from_id INT NOT NULL, contact_to_id INT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, subject VARCHAR(255) DEFAULT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BF5476CAE2E1F88A (contact_from_id), INDEX IDX_BF5476CA1D1F5A6A (contact_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAE2E1F88A FOREIGN KEY (contact_from_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA1D1F5A6A FOREIGN KEY (contact_to_id) REFERENCES contact (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAE2E1F88A');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA1D1F5A6A');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE notification');
    }
}
