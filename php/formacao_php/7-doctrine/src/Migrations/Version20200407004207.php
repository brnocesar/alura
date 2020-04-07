<?php

declare(strict_types=1);

namespace Alura\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200407004207 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cursos (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE curso_aluno (curso_id INT NOT NULL, aluno_id INT NOT NULL, INDEX IDX_6F96721A87CB4A1F (curso_id), INDEX IDX_6F96721AB2DDF7F4 (aluno_id), PRIMARY KEY(curso_id, aluno_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE telefones (id INT AUTO_INCREMENT NOT NULL, aluno_id INT DEFAULT NULL, numero VARCHAR(19) NOT NULL, INDEX IDX_219AAC26B2DDF7F4 (aluno_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alunos (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE curso_aluno ADD CONSTRAINT FK_6F96721A87CB4A1F FOREIGN KEY (curso_id) REFERENCES cursos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE curso_aluno ADD CONSTRAINT FK_6F96721AB2DDF7F4 FOREIGN KEY (aluno_id) REFERENCES alunos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE telefones ADD CONSTRAINT FK_219AAC26B2DDF7F4 FOREIGN KEY (aluno_id) REFERENCES alunos (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE curso_aluno DROP FOREIGN KEY FK_6F96721A87CB4A1F');
        $this->addSql('ALTER TABLE curso_aluno DROP FOREIGN KEY FK_6F96721AB2DDF7F4');
        $this->addSql('ALTER TABLE telefones DROP FOREIGN KEY FK_219AAC26B2DDF7F4');
        $this->addSql('DROP TABLE cursos');
        $this->addSql('DROP TABLE curso_aluno');
        $this->addSql('DROP TABLE telefones');
        $this->addSql('DROP TABLE alunos');
    }
}
