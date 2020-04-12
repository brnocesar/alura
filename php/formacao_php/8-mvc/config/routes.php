<?php

use Alura\Cursos\Controller\CursosEmJson;
use Alura\Cursos\Controller\Deslogar;
use Alura\Cursos\Controller\Exclusao;
use Alura\Cursos\Controller\FormularioEdicao;
use Alura\Cursos\Controller\FormularioInsercao;
use Alura\Cursos\Controller\FormularioLogin;
use Alura\Cursos\Controller\ListarCursos;
use Alura\Cursos\Controller\Persistencia;
use Alura\Cursos\Controller\RealizarLogin;

return [
    '/listar-cursos'    => ListarCursos::class,
    '/novo-curso'       => FormularioInsercao::class,
    '/salvar-curso'     => Persistencia::class,
    '/excluir-curso'    => Exclusao::class,
    '/editar-curso'     => FormularioEdicao::class,
    '/login'            => FormularioLogin::class,
    '/realizar-login'   => RealizarLogin::class,
    '/logout'           => Deslogar::class,
    '/buscarCursosEmJson' => CursosEmJson::class,
];