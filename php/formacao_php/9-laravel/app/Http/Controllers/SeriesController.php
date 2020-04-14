<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function listarSeries(Request $request)
    {
        echo "URL: {$request->url()} ";
        var_dump($request->query());

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
