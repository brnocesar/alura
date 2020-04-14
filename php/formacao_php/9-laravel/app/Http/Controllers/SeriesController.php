<?php

namespace App\Http\Controllers;

class SeriesController extends Controller
{
    public function listarSeries()
    {
        $series = [
            'Terrace House',
            'Peaky Blinders',
            'Stranger Things'
        ];

        $html = "<ul>";
        foreach ($series as $serie) {
            $html .= "<li>$serie</li>";
        }
        $html .= "</ul>";

        return $html;
    }
}
