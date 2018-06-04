<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180604204309 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9CB54F29');
        $this->addSql('DROP INDEX UNIQ_D34A04AD9CB54F29 ON product');
        $this->addSql('ALTER TABLE product ADD buy_cost DOUBLE PRECISION DEFAULT \'0\' NOT NULL, DROP delivery_detail_id');
        $this->addSql('ALTER TABLE product_delivery_detail ADD product INT DEFAULT NULL, ADD user INT DEFAULT NULL, ADD cost DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE product_delivery_detail ADD CONSTRAINT FK_29C8380D34A04AD FOREIGN KEY (product) REFERENCES product (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE product_delivery_detail ADD CONSTRAINT FK_29C83808D93D649 FOREIGN KEY (user) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_29C8380D34A04AD ON product_delivery_detail (product)');
        $this->addSql('CREATE INDEX IDX_29C83808D93D649 ON product_delivery_detail (user)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product ADD delivery_detail_id INT DEFAULT NULL, DROP buy_cost');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9CB54F29 FOREIGN KEY (delivery_detail_id) REFERENCES product_delivery_detail (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD9CB54F29 ON product (delivery_detail_id)');
        $this->addSql('ALTER TABLE product_delivery_detail DROP FOREIGN KEY FK_29C8380D34A04AD');
        $this->addSql('ALTER TABLE product_delivery_detail DROP FOREIGN KEY FK_29C83808D93D649');
        $this->addSql('DROP INDEX IDX_29C8380D34A04AD ON product_delivery_detail');
        $this->addSql('DROP INDEX IDX_29C83808D93D649 ON product_delivery_detail');
        $this->addSql('ALTER TABLE product_delivery_detail DROP product, DROP user, DROP cost');
    }
}
