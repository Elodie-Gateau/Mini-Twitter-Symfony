<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250724080453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet ADD id_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3B79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3D660A3B79F37AE5 ON tweet (id_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tweet DROP FOREIGN KEY FK_3D660A3B79F37AE5');
        $this->addSql('DROP INDEX IDX_3D660A3B79F37AE5 ON tweet');
        $this->addSql('ALTER TABLE tweet DROP id_user_id');
    }
}
