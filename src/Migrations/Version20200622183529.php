<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200622183529 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cars (id INT AUTO_INCREMENT NOT NULL, driver VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, cubic INT NOT NULL, power INT NOT NULL, year INT NOT NULL, motor VARCHAR(20) NOT NULL, chassis VARCHAR(255) NOT NULL, kilowatt INT NOT NULL, about VARCHAR(2000) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clients CHANGE address address VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C82E745E237E06 ON clients (name)');
        $this->addSql('ALTER TABLE events CHANGE name name VARCHAR(255) NOT NULL, CHANGE date date DATE NOT NULL, CHANGE category category VARCHAR(255) NOT NULL, CHANGE days_remaining days_remaining INT NOT NULL, CHANGE status status INT NOT NULL, CHANGE checked checked INT NOT NULL');
        $this->addSql('DROP INDEX id ON notes');
        $this->addSql('DROP INDEX id_2 ON notes');
        $this->addSql('ALTER TABLE notes CHANGE title title VARCHAR(255) NOT NULL, CHANGE change_date change_date VARCHAR(255) NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('DROP INDEX categories_name_uindex ON categories');
        $this->addSql('ALTER TABLE categories CHANGE name name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('ALTER TABLE users RENAME INDEX username TO UNIQ_1483A5E9F85E0677');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE cars');
        $this->addSql('ALTER TABLE categories CHANGE name name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX categories_name_uindex ON categories (name)');
        $this->addSql('DROP INDEX UNIQ_C82E745E237E06 ON clients');
        $this->addSql('ALTER TABLE clients CHANGE address address VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE events CHANGE name name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE date date DATE DEFAULT \'NULL\', CHANGE category category VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE days_remaining days_remaining VARCHAR(2) DEFAULT \'\'0\'\' COLLATE utf8_general_ci, CHANGE status status INT DEFAULT NULL, CHANGE checked checked INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notes MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE notes DROP INDEX primary, ADD INDEX id (id)');
        $this->addSql('ALTER TABLE notes CHANGE title title VARCHAR(256) NOT NULL COLLATE utf8_unicode_ci, CHANGE change_date change_date VARCHAR(50) NOT NULL COLLATE utf8_general_ci');
        $this->addSql('CREATE UNIQUE INDEX id_2 ON notes (id)');
        $this->addSql('DROP INDEX UNIQ_1483A5E9E7927C74 ON users');
        $this->addSql('ALTER TABLE users CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE users RENAME INDEX uniq_1483a5e9f85e0677 TO username');
    }
}
