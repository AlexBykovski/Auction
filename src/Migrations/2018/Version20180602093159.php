<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180602093159 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE stake_balance (id INT AUTO_INCREMENT NOT NULL, stake_detail_id INT DEFAULT NULL, registration_stakes INT DEFAULT 0 NOT NULL, referral_stakes INT DEFAULT 0 NOT NULL, discount_stakes INT DEFAULT 0 NOT NULL, simple_stakes INT DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_3C31BF3DEED1B41B (stake_detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stake_balance ADD CONSTRAINT FK_3C31BF3DEED1B41B FOREIGN KEY (stake_detail_id) REFERENCES stake_detail (id)');
        $this->addSql('ALTER TABLE stake_detail ADD stake_balance INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stake_detail ADD CONSTRAINT FK_54BB2D9C3C31BF3D FOREIGN KEY (stake_balance) REFERENCES stake_balance (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_54BB2D9C3C31BF3D ON stake_detail (stake_balance)');

        $this->addSql('INSERT INTO `stake_balance` (`stake_detail_id`)
                          SELECT `stake_detail`.id FROM `stake_detail`');

        $this->addSql('UPDATE `stake_detail` AS sd
                    JOIN `stake_balance` AS sb ON sd.id = sb.stake_detail_id
                    SET sd.stake_balance = sb.id');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stake_detail DROP FOREIGN KEY FK_54BB2D9C3C31BF3D');
        $this->addSql('DROP TABLE stake_balance');
        $this->addSql('DROP INDEX UNIQ_54BB2D9C3C31BF3D ON stake_detail');
        $this->addSql('ALTER TABLE stake_detail DROP stake_balance');
    }
}
