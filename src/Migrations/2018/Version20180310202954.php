<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180310202954 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE about_us_page (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) NOT NULL, information LONGTEXT NOT NULL, assortment INT NOT NULL, countries INT NOT NULL, achievement_image VARCHAR(255) NOT NULL, experience INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bonus (id INT AUTO_INCREMENT NOT NULL, bonus_page_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_9F987F7AFDDD8BEC (bonus_page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bonus_page (id INT AUTO_INCREMENT NOT NULL, title_description VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, title_bonuses VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_page (id INT AUTO_INCREMENT NOT NULL, information LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_page (id INT AUTO_INCREMENT NOT NULL, soon_product_id INT DEFAULT NULL, slider_images LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_F4B98442BF131F5E (soon_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_page (id INT AUTO_INCREMENT NOT NULL, question LONGTEXT NOT NULL, answer LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE soon_product (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bonus ADD CONSTRAINT FK_9F987F7AFDDD8BEC FOREIGN KEY (bonus_page_id) REFERENCES bonus_page (id)');
        $this->addSql('ALTER TABLE main_page ADD CONSTRAINT FK_F4B98442BF131F5E FOREIGN KEY (soon_product_id) REFERENCES soon_product (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bonus DROP FOREIGN KEY FK_9F987F7AFDDD8BEC');
        $this->addSql('ALTER TABLE main_page DROP FOREIGN KEY FK_F4B98442BF131F5E');
        $this->addSql('DROP TABLE about_us_page');
        $this->addSql('DROP TABLE bonus');
        $this->addSql('DROP TABLE bonus_page');
        $this->addSql('DROP TABLE delivery_page');
        $this->addSql('DROP TABLE main_page');
        $this->addSql('DROP TABLE question_page');
        $this->addSql('DROP TABLE soon_product');
    }
}
