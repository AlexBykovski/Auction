<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180721070039 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_meta_data (id INT AUTO_INCREMENT NOT NULL, product INT DEFAULT NULL, additional_data LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_50F536ADD34A04AD (product), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_meta_data ADD CONSTRAINT FK_50F536ADD34A04AD FOREIGN KEY (product) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD meta_data INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD3E558020 FOREIGN KEY (meta_data) REFERENCES product_meta_data (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD3E558020 ON product (meta_data)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD3E558020');
        $this->addSql('DROP TABLE product_meta_data');
        $this->addSql('DROP INDEX UNIQ_D34A04AD3E558020 ON product');
        $this->addSql('ALTER TABLE product DROP meta_data');
    }
}
