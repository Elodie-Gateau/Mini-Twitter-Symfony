<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250724081310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD tweet_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id)');
        $this->addSql('CREATE INDEX IDX_9474526C1041E39B ON comment (tweet_id)');
        $this->addSql('ALTER TABLE media ADD tweet_id INT NOT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10C1041E39B ON media (tweet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C1041E39B');
        $this->addSql('DROP INDEX IDX_9474526C1041E39B ON comment');
        $this->addSql('ALTER TABLE comment DROP tweet_id');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C1041E39B');
        $this->addSql('DROP INDEX IDX_6A2CA10C1041E39B ON media');
        $this->addSql('ALTER TABLE media DROP tweet_id');
    }
}
