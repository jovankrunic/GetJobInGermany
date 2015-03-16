<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150208083718 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categories CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE jobs ADD slug TEXT NOT NULL, ADD city VARCHAR(150) NOT NULL, ADD more_cities TINYINT(1) NOT NULL, CHANGE category_id category_id INT DEFAULT NULL, CHANGE `current_time` `current_time` DATETIME NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categories CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE jobs DROP slug, DROP city, DROP more_cities, CHANGE category_id category_id INT NOT NULL, CHANGE `current_time` `current_time` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
