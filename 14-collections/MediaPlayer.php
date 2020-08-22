<?php

class MediaPlayer
{
    private $media;
    private $history;
    private $downloads;
    private $ranking;

    public function __construct()
    {
        $this->media = new SplDoublyLinkedList();
        $this->media->rewind();
        $this->history = new SplStack();
        $this->downloads = new SplQueue();
        $this->ranking = new Ranking();
    }

    public function AddManyMedia(SplFixedArray $media): self
    {
        for($media->rewind(); $media->valid(); $media->next()) {

            $this->media->push($media->current());
        }

        // retorna o ponteiro para o primeiro item da coleção
        $this->media->rewind();
        return $this;
    }

    public function addOneMedia(string $media): self
    {
        // adiciona um item no final da coleção
        $this->media->push($media);
        return $this;
    }

    public function play(): self
    {
        if( $this->media->count() == 0 ) {
            echo "\n=> There is no media available\n";
            return $this;
        }

        $this->history->push($this->media->current());
        $this->media->current()->play();
        return $this;
    }

    public function playLast(): self
    {
        if( $this->history->count() == 0 ) {
            return $this;
        }

        echo "\n=> Playing last media: {$this->history->pop()}\n";
        return $this;
    }

    public function forward(): self
    {
        // avança o ponteiro para o item seguinte
        $this->media->next();
        
        if ( !$this->media->valid() ) {
            $this->media->rewind();
        }

        return $this;
    }

    public function back(): self
    {
        // volta o ponteiro para o item anterior
        $this->media->prev();
        
        if ( !$this->media->valid() ) {
            $this->media->rewind();
        }

        return $this;
    }

    public function showPlaylist(): self
    {
        echo "\nPlaylist ({$this->media->count()})\n";

        for($this->media->rewind(); $this->media->valid(); $this->media->next()) {

            echo "\tMedia: {$this->media->current()}\n";
        }

        $this->media->rewind();
        return $this;
    }

    public function addOneMediaToBeginning(string $media): self
    {
        // adiciona um item no inicio da coleção
        $this->media->unshift($media);
        return $this;
    }

    public function takeOutOneMediaFromBeginning(): self
    {
        // retira um item do inicio da lista
        $this->media->shift();
        return $this;
    }

    public function takeOutOneMedia(): self
    {
        // retira um item do final da lista
        $this->media->pop();
        return $this;
    }

    public function downloadPlaylist(): self
    {
        for($this->media->rewind(); $this->media->valid(); $this->media->next()) {

            $this->downloads->push($this->media->current());
        }

        for($this->downloads->rewind(); $this->downloads->valid(); $this->downloads->next()) {

            echo "Downloading: {$this->downloads->current()}...\n";
        }

        return $this;
    }

    public function showRanking()
    {
        foreach($this->media as $media) {
            $this->ranking->insert($media);
        }

        echo "\nRanking ({$this->ranking->count()})\n";
        foreach($this->ranking as $media) {
            echo "\t{$media->getName()} - {$media->getTimesPlayed()}\n";
        }
    }
}

