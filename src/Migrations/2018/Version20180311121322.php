<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180311121322 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE about_us_page CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE information information LONGTEXT DEFAULT NULL, CHANGE achievement_image achievement_image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE about_us_page CHANGE image image VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE information information LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE achievement_image achievement_image VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
