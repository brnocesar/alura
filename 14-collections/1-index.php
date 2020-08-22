<?php

require 'Ranking.php';
require 'MediaPlayer.php';
require 'Song.php';


// cria array de tamanho fixo
$songs = new SplFixedArray(2);

// aceita apenas inteiros como índice
$songs[0] = new Song("Batatinha");
$songs[1] = new Song("Abobrinha");

// define novo tamanho 
$songs->setSize(4);

$songs[2] = new Song("Bananinha");
$songs[3] = new Song("Tomatinho");

// verifica o tamanho da coleção
echo "There are {$songs->getSize()} songs in the collection.\n";


// instancia player de media
$player = new MediaPlayer();

// adiciona VÁRIAS musicas na playlist do player
$player->AddManyMedia($songs);
$player->showPlaylist();

// adiciona UMA música na playlist
$player->addOneMedia(new Song("Cenourinha"));
$player->showPlaylist();

// reproduz música
$player->play();

// avança para próxima música e reproduz
$player->forward();
$player->play();

$player->takeOutOneMedia();
$player->takeOutOneMediaFromBeginning();
$player->showPlaylist();

// avança várias músicas e reproduz: do histórico e da playlist
$player->forward();
$player->forward();
$player->forward();
$player->forward();
$player->playLast();
$player->play();

$player->downloadPlaylist();