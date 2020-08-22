<?php 

class Song {
    private $name;
    private $timesPlayed;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->timesPlayed = 0;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTimesPlayed(): int
    {
        return $this->timesPlayed;
    }

    public function play()
    {
        echo "\n=> Playing media: {$this->getName()}\n";
        $this->timesPlayed++;
    }
}