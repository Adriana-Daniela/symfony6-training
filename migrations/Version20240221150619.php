<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221150619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, email, roles, password, preferred_channel, birthday FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:simple_array)
        , password VARCHAR(255) NOT NULL, preferred_channel VARCHAR(20) DEFAULT NULL, birthday DATE DEFAULT NULL --(DC2Type:date_immutable)
        , last_connected_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO user (id, username, email, roles, password, preferred_channel, birthday) SELECT id, username, email, roles, password, preferred_channel, birthday FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, email, roles, password, preferred_channel, birthday FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:simple_array)
        , password VARCHAR(255) NOT NULL, preferred_channel VARCHAR(20) DEFAULT NULL, birthday DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, username, email, roles, password, preferred_channel, birthday) SELECT id, username, email, roles, password, preferred_channel, birthday FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
