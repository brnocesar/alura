<?php

require 'Album.php';
require 'Song.php';

$albuns = new SplObjectStorage();

$leguminhos = new Album("Leguminhos");
$albuns->attach($leguminhos);

$frutinhas = new Album("Frutinhas");
$albuns->attach($frutinhas);

// var_dump($albuns);

$songsLeguminhos = new SplFixedArray(3);
$songsLeguminhos[0] = new Song("Batatinha");
$songsLeguminhos[1] = new Song("Abobrinha");
$songsLeguminhos[2] = new Song("Cenourinha");

$songsFrutinhas = new SplFixedArray(2);
$songsFrutinhas[0] = new Song("Bananinha");
$songsFrutinhas[1] = new Song("Tomatinho");

$albuns[$leguminhos] = $songsLeguminhos;
$albuns[$frutinhas] = $songsFrutinhas;

foreach ($albuns as $album) {
    
    echo "\nAlbum: {$album->getName()}\n";

    foreach ($albuns[$album] as $song) {
        
        echo "\tMúsica: {$song->getName()}\n";
    }
}