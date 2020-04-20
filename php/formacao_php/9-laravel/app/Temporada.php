<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temporada extends Model
{
    protected $table = 'temporadas';
    protected $fillable = ['numero'];

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }

    public function episodios()
    {
        return $this->hasMany(Episodio::class);
    }
}
