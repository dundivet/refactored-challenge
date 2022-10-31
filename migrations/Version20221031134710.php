<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221031134710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT fk_389b7835be9ecd7');
        $this->addSql('DROP INDEX idx_389b7835be9ecd7');
        $this->addSql('ALTER TABLE tag DROP to_do_id');
        $this->addSql('ALTER TABLE to_do ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE to_do ADD CONSTRAINT FK_1249EDA07E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1249EDA07E3C61F9 ON to_do (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE to_do DROP CONSTRAINT FK_1249EDA07E3C61F9');
        $this->addSql('DROP INDEX IDX_1249EDA07E3C61F9');
        $this->addSql('ALTER TABLE to_do DROP owner_id');
        $this->addSql('ALTER TABLE tag ADD to_do_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT fk_389b7835be9ecd7 FOREIGN KEY (to_do_id) REFERENCES to_do (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_389b7835be9ecd7 ON tag (to_do_id)');
    }
}
