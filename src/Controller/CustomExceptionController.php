<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class CustomExceptionController
{
    public function error()
    {
        $response = new Response(null, Response::HTTP_NOT_FOUND);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}

