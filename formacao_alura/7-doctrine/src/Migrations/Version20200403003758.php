<?php

declare(strict_types=1);

namespace Alura\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200403003758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE cursos (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nome VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE curso_aluno (curso_id INTEGER NOT NULL, aluno_id INTEGER NOT NULL, PRIMARY KEY(curso_id, aluno_id))');
        $this->addSql('CREATE INDEX IDX_6F96721A87CB4A1F ON curso_aluno (curso_id)');
        $this->addSql('CREATE INDEX IDX_6F96721AB2DDF7F4 ON curso_aluno (aluno_id)');
        $this->addSql('DROP INDEX IDX_219AAC26B2DDF7F4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__telefones AS SELECT id, aluno_id, numero FROM telefones');
        $this->addSql('DROP TABLE telefones');
        $this->addSql('CREATE TABLE telefones (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, aluno_id INTEGER DEFAULT NULL, numero VARCHAR(19) NOT NULL COLLATE BINARY, CONSTRAINT FK_219AAC26B2DDF7F4 FOREIGN KEY (aluno_id) REFERENCES alunos (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO telefones (id, aluno_id, numero) SELECT id, aluno_id, numero FROM __temp__telefones');
        $this->addSql('DROP TABLE __temp__telefones');
        $this->addSql('CREATE INDEX IDX_219AAC26B2DDF7F4 ON telefones (aluno_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE cursos');
        $this->addSql('DROP TABLE curso_aluno');
        $this->addSql('DROP INDEX IDX_219AAC26B2DDF7F4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__telefones AS SELECT id, aluno_id, numero FROM telefones');
        $this->addSql('DROP TABLE telefones');
        $this->addSql('CREATE TABLE telefones (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, aluno_id INTEGER DEFAULT NULL, numero VARCHAR(19) NOT NULL)');
        $this->addSql('INSERT INTO telefones (id, aluno_id, numero) SELECT id, aluno_id, numero FROM __temp__telefones');
        $this->addSql('DROP TABLE __temp__telefones');
        $this->addSql('CREATE INDEX IDX_219AAC26B2DDF7F4 ON telefones (aluno_id)');
    }
}
