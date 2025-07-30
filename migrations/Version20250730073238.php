<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250730073238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD is_signaled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C1041E39B');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP is_signaled');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C1041E39B');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
