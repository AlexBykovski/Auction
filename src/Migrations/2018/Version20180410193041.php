<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180410193041 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auto_stake ADD auction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auto_stake ADD CONSTRAINT FK_674CB39E57B8F0DE FOREIGN KEY (auction_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_674CB39E57B8F0DE ON auto_stake (auction_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auto_stake DROP FOREIGN KEY FK_674CB39E57B8F0DE');
        $this->addSql('DROP INDEX IDX_674CB39E57B8F0DE ON auto_stake');
        $this->addSql('ALTER TABLE auto_stake DROP auction_id');
    }
}
