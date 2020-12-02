<?php

namespace Learning\Solid\Service;

use Learning\Solid\Model\AluraMais;
use Learning\Solid\Model\Curso;

class Assistidor
{
    public function assisteCurso(Curso $curso)
    {
        foreach ($curso->recuperarVideos() as $video) {
            $video->assistir();
        }
    }

    public function assisteAluraMais(AluraMais $aluraMais)
    {
        $aluraMais->assistir();
    }
}
