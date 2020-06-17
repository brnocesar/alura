<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = ['name'];
    protected $perPage = 3;
    protected $appends = ['links'];


    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }


    public function setNameAttribute($name): void
    {
        $this->attributes['name'] = $name . ' ;)';
    }

    public function getLinksAttribute($links): array
    {
        return [
            "self" => "/api/series/{$this->id}",
            "episodios" => "/api/series/{$this->id}/episodios"
        ];
    }
}
