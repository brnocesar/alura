<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = ['name'];
    protected $perPage = 3;


    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }


    public function setNameAttribute($name): void
    {
        $this->attributes['name'] = $name . ' ;)';
    }
}
