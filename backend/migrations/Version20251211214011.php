<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251211214011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE workout_exercise ADD repetition INT NOT NULL');
        $this->addSql('ALTER TABLE workout_exercise ADD weight INT NOT NULL');
        $this->addSql('ALTER TABLE workout_exercise ADD exercise_id INT NOT NULL');
        $this->addSql('ALTER TABLE workout_exercise ALTER workout_id SET NOT NULL');
        $this->addSql('ALTER TABLE workout_exercise ADD CONSTRAINT FK_76AB38AAE934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_76AB38AAE934951A ON workout_exercise (exercise_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE workout_exercise DROP CONSTRAINT FK_76AB38AAE934951A');
        $this->addSql('DROP INDEX IDX_76AB38AAE934951A');
        $this->addSql('ALTER TABLE workout_exercise DROP repetition');
        $this->addSql('ALTER TABLE workout_exercise DROP weight');
        $this->addSql('ALTER TABLE workout_exercise DROP exercise_id');
        $this->addSql('ALTER TABLE workout_exercise ALTER workout_id DROP NOT NULL');
    }
}
