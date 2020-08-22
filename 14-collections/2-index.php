<?php

require 'Ranking.php';
require 'MediaPlayer.php';
require 'Song.php';

$songs = new SplFixedArray(4);
$songs[0] = new Song("Batatinha");
$songs[1] = new Song("Abobrinha");
$songs[2] = new Song("Bananinha");
$songs[3] = new Song("Tomatinho");

$player = new MediaPlayer();
$player->AddManyMedia($songs);

$player->play();
$player->play();
$player->play();
$player->play();

$player->forward();
$player->forward();
$player->play();

$player->forward();
$player->play();

$player->forward();
$player->forward();
$player->forward();
$player->forward();
$player->forward();
$player->forward();
$player->forward();
$player->play();


$player->showRanking();
