<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = ['season', 'number', 'watched', 'serie_id'];

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
}
