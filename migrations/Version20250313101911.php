<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250313101911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD responsible_member_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25BB889CDE FOREIGN KEY (responsible_member_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25BB889CDE ON task (responsible_member_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25BB889CDE');
        $this->addSql('DROP INDEX IDX_527EDB25BB889CDE ON task');
        $this->addSql('ALTER TABLE task DROP responsible_member_id');
    }
}
