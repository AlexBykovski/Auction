<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180602112406 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE auto_stake_balance (id INT AUTO_INCREMENT NOT NULL, auto_stake_id INT DEFAULT NULL, registration_stakes INT DEFAULT 0 NOT NULL, referral_stakes INT DEFAULT 0 NOT NULL, discount_stakes INT DEFAULT 0 NOT NULL, simple_stakes INT DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_A0A36C37214EA06D (auto_stake_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auto_stake_balance ADD CONSTRAINT FK_A0A36C37214EA06D FOREIGN KEY (auto_stake_id) REFERENCES auto_stake (id)');
        $this->addSql('ALTER TABLE auto_stake ADD balance INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auto_stake ADD CONSTRAINT FK_674CB39EACF41FFE FOREIGN KEY (balance) REFERENCES auto_stake_balance (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_674CB39EACF41FFE ON auto_stake (balance)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auto_stake DROP FOREIGN KEY FK_674CB39EACF41FFE');
        $this->addSql('DROP TABLE auto_stake_balance');
        $this->addSql('DROP INDEX UNIQ_674CB39EACF41FFE ON auto_stake');
        $this->addSql('ALTER TABLE auto_stake DROP balance');
    }
}
