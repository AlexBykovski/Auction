<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180311123518 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE main_page CHANGE slider_images slider_images LONGTEXT DEFAULT NULL');
        $this->addSql('INSERT INTO `soon_product` (`image`, `name`, `description`) VALUES ("", "", "")');
        $this->addSql('INSERT INTO `main_page` (`soon_product_id`)
                          SELECT soon_product.id FROM `soon_product` LIMIT 1');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE `main_page` SET soon_product_id = NULL');
        $this->addSql('DELETE FROM `main_page`');
        $this->addSql('DELETE FROM `soon_product`');
        $this->addSql('ALTER TABLE main_page CHANGE slider_images slider_images LONGTEXT NOT NULL COLLATE utf8_unicode_ci');

    }
}
