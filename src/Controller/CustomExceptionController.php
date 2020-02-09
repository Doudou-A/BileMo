<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class CustomExceptionController
{
    public function error()
    {
        $error = 'Not Found';

        return new Response($error, Response::HTTP_NOT_FOUND);
    }

}

