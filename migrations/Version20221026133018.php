<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026133018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE to_do_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, to_do_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_389B7835BE9ECD7 ON tag (to_do_id)');
        $this->addSql('CREATE TABLE to_do (id INT NOT NULL, parent_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, due TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, completed BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1249EDA0727ACA70 ON to_do (parent_id)');
        $this->addSql('CREATE TABLE to_do_tag (to_do_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(to_do_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_70B1718B5BE9ECD7 ON to_do_tag (to_do_id)');
        $this->addSql('CREATE INDEX IDX_70B1718BBAD26311 ON to_do_tag (tag_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7835BE9ECD7 FOREIGN KEY (to_do_id) REFERENCES to_do (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE to_do ADD CONSTRAINT FK_1249EDA0727ACA70 FOREIGN KEY (parent_id) REFERENCES to_do (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE to_do_tag ADD CONSTRAINT FK_70B1718B5BE9ECD7 FOREIGN KEY (to_do_id) REFERENCES to_do (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE to_do_tag ADD CONSTRAINT FK_70B1718BBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE to_do_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT FK_389B7835BE9ECD7');
        $this->addSql('ALTER TABLE to_do DROP CONSTRAINT FK_1249EDA0727ACA70');
        $this->addSql('ALTER TABLE to_do_tag DROP CONSTRAINT FK_70B1718B5BE9ECD7');
        $this->addSql('ALTER TABLE to_do_tag DROP CONSTRAINT FK_70B1718BBAD26311');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE to_do');
        $this->addSql('DROP TABLE to_do_tag');
        $this->addSql('DROP TABLE "user"');
    }
}
