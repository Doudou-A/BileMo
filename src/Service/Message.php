<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

class Message
{
    public function addSuccess()
    {
        return new Response('Ajout effectué avec succès !', Response::HTTP_CREATED);
    }

    public function removeSuccess()
    {
        return new Response('Suppression effectuée avec succès !', Response::HTTP_CREATED);
    }

    public function modifySuccess()
    {
        return new Response('Modification effectuée avec succès !', Response::HTTP_CREATED);
    }
}