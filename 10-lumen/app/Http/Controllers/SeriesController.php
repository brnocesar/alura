<?php

namespace App\Http\Controllers;

use App\Models\Serie;

class SeriesController extends Controller
{
    public function index()
    {
        return Serie::all();
    }
}
