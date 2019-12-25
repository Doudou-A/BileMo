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

    public function RelationFail()
    {
        return new Response('Ce téléphone n\'est pas disponible !', Response::HTTP_CREATED);
    }

    public function RemoveDenied()
    {
        return new Response('Vous n\'êtes pas autorisé à faire cette action !', Response::HTTP_CREATED);
    }
}