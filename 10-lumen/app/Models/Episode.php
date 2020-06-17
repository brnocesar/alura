<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = ['season', 'number', 'watched', 'serie_id'];
    protected $appends = ['links'];

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }


    public function getWatchedAttribute($watched): bool
    {
        return $watched;
    }

    public function getSerieIdAttribute($serie_id): int
    {
        return $serie_id;
    }

    public function getLinksAttribute($links): array
    {
        return [
            "self" => "/episodios/{$this->id}",
            "series" => "/series/{$this->id}"
        ];
    }
}
