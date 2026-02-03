<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260127183843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competiciones.category_competicion (id_category INT NOT NULL, id_competicion INT NOT NULL, PRIMARY KEY (id_category, id_competicion))');
        $this->addSql('CREATE INDEX IDX_B1A864D15697F554 ON competiciones.category_competicion (id_category)');
        $this->addSql('CREATE INDEX IDX_B1A864D160D68E60 ON competiciones.category_competicion (id_competicion)');
        $this->addSql('ALTER TABLE competiciones.category_competicion ADD CONSTRAINT FK_B1A864D15697F554 FOREIGN KEY (id_category) REFERENCES competiciones.category (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE competiciones.category_competicion ADD CONSTRAINT FK_B1A864D160D68E60 FOREIGN KEY (id_competicion) REFERENCES competiciones.competicion (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE category_competicion DROP CONSTRAINT fk_cf5f01fc12469de2');
        $this->addSql('ALTER TABLE category_competicion DROP CONSTRAINT fk_cf5f01fcd9407152');
        $this->addSql('DROP TABLE category_competicion');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_competicion (category_id INT NOT NULL, competicion_id INT NOT NULL, PRIMARY KEY (category_id, competicion_id))');
        $this->addSql('CREATE INDEX idx_cf5f01fcd9407152 ON category_competicion (competicion_id)');
        $this->addSql('CREATE INDEX idx_cf5f01fc12469de2 ON category_competicion (category_id)');
        $this->addSql('ALTER TABLE category_competicion ADD CONSTRAINT fk_cf5f01fc12469de2 FOREIGN KEY (category_id) REFERENCES competiciones.category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_competicion ADD CONSTRAINT fk_cf5f01fcd9407152 FOREIGN KEY (competicion_id) REFERENCES competiciones.competicion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE competiciones.category_competicion DROP CONSTRAINT FK_B1A864D15697F554');
        $this->addSql('ALTER TABLE competiciones.category_competicion DROP CONSTRAINT FK_B1A864D160D68E60');
        $this->addSql('DROP TABLE competiciones.category_competicion');
    }
}
