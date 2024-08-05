<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240805223227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE division_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE result_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE team_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tournament_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE division (id INT NOT NULL, tournament_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, team_qty INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1017471433D1A3E7 ON division (tournament_id)');
        $this->addSql('CREATE TABLE result (id INT NOT NULL, team_id INT DEFAULT NULL, first_team VARCHAR(255) NOT NULL, second_team VARCHAR(255) NOT NULL, first_team_id INT NOT NULL, second_team_id INT NOT NULL, score VARCHAR(255) NOT NULL, stage VARCHAR(255) NOT NULL, winner_id INT NOT NULL, tournament_id INT NOT NULL, match_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_136AC113296CD8AE ON result (team_id)');
        $this->addSql('CREATE INDEX IDX_136AC113C27C9369 ON result (stage)');
        $this->addSql('CREATE TABLE team (id INT NOT NULL, division_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C4E0A61F41859289 ON team (division_id)');
        $this->addSql('CREATE TABLE tournament (id INT NOT NULL, name VARCHAR(255) NOT NULL, division_qty INT NOT NULL, playoff_team_qty INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE division ADD CONSTRAINT FK_1017471433D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F41859289 FOREIGN KEY (division_id) REFERENCES division (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE division_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE result_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE team_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tournament_id_seq CASCADE');
        $this->addSql('ALTER TABLE division DROP CONSTRAINT FK_1017471433D1A3E7');
        $this->addSql('ALTER TABLE result DROP CONSTRAINT FK_136AC113296CD8AE');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F41859289');
        $this->addSql('DROP TABLE division');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE tournament');
    }
}
