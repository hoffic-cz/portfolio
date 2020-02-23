<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200222234340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Setting up the activity table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE activity (
    time        TIMESTAMPTZ     NOT NULL,
    visitor     TEXT            NOT NULL,
    type        TEXT            NOT NULL,
    name        TEXT            NOT NULL,
    params      TEXT            NULL
)
SQL
        );
        $this->addSql("SELECT create_hypertable('activity', 'time')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE activity');
    }
}
