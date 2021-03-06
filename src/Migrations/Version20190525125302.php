<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190525125302 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE articles ADD category_id INT NOT NULL, ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('CREATE INDEX IDX_23A0E6612469DE2 ON articles (category_id)');
        $this->addSql('CREATE INDEX IDX_23A0E66F675F31B ON articles (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('DROP INDEX IDX_23A0E6612469DE2 ON articles');
        $this->addSql('DROP INDEX IDX_23A0E66F675F31B ON articles');
        $this->addSql('ALTER TABLE articles DROP category_id, DROP author_id');
    }
}
