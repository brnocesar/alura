<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController
{
    /**
     * @Route("/hello", methods={"GET"})
    */
    public function helloWorldAction(Request $request): Response
    {
        return new JsonResponse([
            "message"     => "Hello world",
            "pathInfo"    => $request->getPathInfo(),
            "parameter"   => $request->query->get('parameter'),
            "queryParams" => $request->query->all(),
        ]);
    }
}