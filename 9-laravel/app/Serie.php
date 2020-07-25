<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Serie extends Model
{
    protected $fillable = ['nome', 'capa'];

    public function getCapaUrlAttribute()
    {
        return Storage::url($this->capa ?? "default/no-image.png");
    }

    public function temporadas()
    {
        return $this->hasMany(Temporada::class);
    }
}
