<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180310195632 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE auto_stake (id INT AUTO_INCREMENT NOT NULL, stake_detail_id INT DEFAULT NULL, is_active TINYINT(1) NOT NULL, is_win_end TINYINT(1) NOT NULL, end_at DATETIME NOT NULL, count INT NOT NULL, INDEX IDX_674CB39EEED1B41B (stake_detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_detail (id INT AUTO_INCREMENT NOT NULL, news TINYINT(1) DEFAULT \'0\' NOT NULL, novelty_appearance TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_delivery_detail (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, post_index VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, note LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_timer (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, time INT NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_CB8CA5464584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stake_detail (id INT AUTO_INCREMENT NOT NULL, count VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stake_expense (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, stake_detail_id INT DEFAULT NULL, count VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_BDFF2D654584665A (product_id), INDEX IDX_BDFF2D65EED1B41B (stake_detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stake_purchase (id INT AUTO_INCREMENT NOT NULL, stake_detail_id INT DEFAULT NULL, cost INT NOT NULL, count INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_63EA8791EED1B41B (stake_detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE support_question (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, question LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_241C271A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_delivery_detail (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, post_index VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, note LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auto_stake ADD CONSTRAINT FK_674CB39EEED1B41B FOREIGN KEY (stake_detail_id) REFERENCES stake_detail (id)');
        $this->addSql('ALTER TABLE product_timer ADD CONSTRAINT FK_CB8CA5464584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE stake_expense ADD CONSTRAINT FK_BDFF2D654584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE stake_expense ADD CONSTRAINT FK_BDFF2D65EED1B41B FOREIGN KEY (stake_detail_id) REFERENCES stake_detail (id)');
        $this->addSql('ALTER TABLE stake_purchase ADD CONSTRAINT FK_63EA8791EED1B41B FOREIGN KEY (stake_detail_id) REFERENCES stake_detail (id)');
        $this->addSql('ALTER TABLE support_question ADD CONSTRAINT FK_241C271A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE product ADD winner_id INT DEFAULT NULL, ADD delivery_detail_id INT DEFAULT NULL, ADD timer_id INT DEFAULT NULL, ADD is_processed TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_ended TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9CB54F29 FOREIGN KEY (delivery_detail_id) REFERENCES product_delivery_detail (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADEE98D9B9 FOREIGN KEY (timer_id) REFERENCES product_timer (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD5DFCD4B8 ON product (winner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD9CB54F29 ON product (delivery_detail_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADEE98D9B9 ON product (timer_id)');
        $this->addSql('ALTER TABLE user ADD delivery_detail_id INT DEFAULT NULL, ADD notification_detail_id INT DEFAULT NULL, ADD stake_detail_id INT DEFAULT NULL, ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD sex TINYINT(1) DEFAULT \'1\' NOT NULL, ADD age INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499CB54F29 FOREIGN KEY (delivery_detail_id) REFERENCES user_delivery_detail (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497E3944E4 FOREIGN KEY (notification_detail_id) REFERENCES notification_detail (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649EED1B41B FOREIGN KEY (stake_detail_id) REFERENCES stake_detail (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6499CB54F29 ON user (delivery_detail_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497E3944E4 ON user (notification_detail_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649EED1B41B ON user (stake_detail_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6497E3944E4');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9CB54F29');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADEE98D9B9');
        $this->addSql('ALTER TABLE auto_stake DROP FOREIGN KEY FK_674CB39EEED1B41B');
        $this->addSql('ALTER TABLE stake_expense DROP FOREIGN KEY FK_BDFF2D65EED1B41B');
        $this->addSql('ALTER TABLE stake_purchase DROP FOREIGN KEY FK_63EA8791EED1B41B');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649EED1B41B');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6499CB54F29');
        $this->addSql('DROP TABLE auto_stake');
        $this->addSql('DROP TABLE notification_detail');
        $this->addSql('DROP TABLE product_delivery_detail');
        $this->addSql('DROP TABLE product_timer');
        $this->addSql('DROP TABLE stake_detail');
        $this->addSql('DROP TABLE stake_expense');
        $this->addSql('DROP TABLE stake_purchase');
        $this->addSql('DROP TABLE support_question');
        $this->addSql('DROP TABLE user_delivery_detail');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD5DFCD4B8');
        $this->addSql('DROP INDEX IDX_D34A04AD5DFCD4B8 ON product');
        $this->addSql('DROP INDEX UNIQ_D34A04AD9CB54F29 ON product');
        $this->addSql('DROP INDEX UNIQ_D34A04ADEE98D9B9 ON product');
        $this->addSql('ALTER TABLE product DROP winner_id, DROP delivery_detail_id, DROP timer_id, DROP is_processed, DROP is_ended');
        $this->addSql('DROP INDEX UNIQ_8D93D6499CB54F29 ON `user`');
        $this->addSql('DROP INDEX UNIQ_8D93D6497E3944E4 ON `user`');
        $this->addSql('DROP INDEX UNIQ_8D93D649EED1B41B ON `user`');
        $this->addSql('ALTER TABLE `user` DROP delivery_detail_id, DROP notification_detail_id, DROP stake_detail_id, DROP first_name, DROP last_name, DROP sex, DROP age');
    }
}
