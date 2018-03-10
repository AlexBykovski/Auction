<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180310124919 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, main_photo VARCHAR(255) NOT NULL, photos LONGTEXT DEFAULT NULL, cost INT DEFAULT 100 NOT NULL, name VARCHAR(255) NOT NULL, characteristics LONGTEXT NOT NULL, conditions LONGTEXT NOT NULL, categories LONGTEXT DEFAULT NULL, start_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE goods');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE goods (id INT AUTO_INCREMENT NOT NULL, main_photo VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, photos LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, cost INT DEFAULT 100 NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, characteristics LONGTEXT NOT NULL COLLATE utf8_unicode_ci, conditions LONGTEXT NOT NULL COLLATE utf8_unicode_ci, categories LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, start_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE product');
    }
}
