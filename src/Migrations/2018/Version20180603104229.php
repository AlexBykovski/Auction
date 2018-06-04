<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180603104229 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE referral_system (id INT AUTO_INCREMENT NOT NULL, percent_from_referral INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD referrer INT DEFAULT NULL, ADD referral_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649ED646567 FOREIGN KEY (referrer) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649ED646567 ON user (referrer)');

        $this->addSql('INSERT INTO `referral_system` (`percent_from_referral`) VALUES (0)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE referral_system');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649ED646567');
        $this->addSql('DROP INDEX IDX_8D93D649ED646567 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP referrer, DROP referral_code');
    }
}
