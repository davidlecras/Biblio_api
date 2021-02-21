<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210221150910 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auteur DROP FOREIGN KEY FK_55AB1401B063272');
        $this->addSql('DROP INDEX IDX_55AB1401B063272 ON auteur');
        $this->addSql('ALTER TABLE auteur CHANGE nationalite_id nationality_id INT NOT NULL');
        $this->addSql('ALTER TABLE auteur ADD CONSTRAINT FK_55AB1401C9DA55 FOREIGN KEY (nationality_id) REFERENCES nationality (id)');
        $this->addSql('CREATE INDEX IDX_55AB1401C9DA55 ON auteur (nationality_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auteur DROP FOREIGN KEY FK_55AB1401C9DA55');
        $this->addSql('DROP INDEX IDX_55AB1401C9DA55 ON auteur');
        $this->addSql('ALTER TABLE auteur CHANGE nationality_id nationalite_id INT NOT NULL');
        $this->addSql('ALTER TABLE auteur ADD CONSTRAINT FK_55AB1401B063272 FOREIGN KEY (nationalite_id) REFERENCES nationality (id)');
        $this->addSql('CREATE INDEX IDX_55AB1401B063272 ON auteur (nationalite_id)');
    }
}
