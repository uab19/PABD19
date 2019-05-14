<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190514080711 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE forum_subcategory (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(1000) NOT NULL, INDEX forum_subcategory_forum_category_id_fk (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_topic (id INT AUTO_INCREMENT NOT NULL, subcategory_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, message TEXT NOT NULL, date_added DATETIME NOT NULL, INDEX forum_topic_forum_subcategory_id_fk (subcategory_id), INDEX forum_topic_user_id_fk (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, file LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_reply (id INT AUTO_INCREMENT NOT NULL, reply_id INT DEFAULT NULL, topic_id INT DEFAULT NULL, user_id INT DEFAULT NULL, message TEXT NOT NULL, date_added DATETIME NOT NULL, INDEX forum_reply_forum_reply_id_fk (reply_id), INDEX forum_reply_forum_topic_id_fk (topic_id), INDEX forum_reply_user_id_fk (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_subcategory ADD CONSTRAINT FK_BE827EA12469DE2 FOREIGN KEY (category_id) REFERENCES forum_category (id)  ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CC5DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES forum_subcategory (id)  ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CCA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE forum_reply ADD CONSTRAINT FK_E5DC60378A0E4E7F FOREIGN KEY (reply_id) REFERENCES forum_reply (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE forum_reply ADD CONSTRAINT FK_E5DC60371F55203D FOREIGN KEY (topic_id) REFERENCES forum_topic (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE forum_reply ADD CONSTRAINT FK_E5DC6037A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_topic DROP FOREIGN KEY FK_853478CC5DC6FE57');
        $this->addSql('ALTER TABLE forum_subcategory DROP FOREIGN KEY FK_BE827EA12469DE2');
        $this->addSql('ALTER TABLE forum_reply DROP FOREIGN KEY FK_E5DC60371F55203D');
        $this->addSql('ALTER TABLE forum_topic DROP FOREIGN KEY FK_853478CCA76ED395');
        $this->addSql('ALTER TABLE forum_reply DROP FOREIGN KEY FK_E5DC6037A76ED395');
        $this->addSql('ALTER TABLE forum_reply DROP FOREIGN KEY FK_E5DC60378A0E4E7F');
        $this->addSql('DROP TABLE forum_subcategory');
        $this->addSql('DROP TABLE forum_category');
        $this->addSql('DROP TABLE forum_topic');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE forum_reply');
    }
}
