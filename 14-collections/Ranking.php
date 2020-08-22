<?php

use SplHeap;

class Ranking extends SplHeap
{
    protected function compare($songA, $songB): int
    {
        if($songA->getTimesPlayed() === $songB->getTimesPlayed()) {
            return 0;
        }
        
        if($songA->getTimesPlayed() < $songB->getTimesPlayed()) {
            return -1;
        }

        return 1;
    }
}
