<?php

namespace App\Controller;

use App\Service\PhoneManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneDeleteController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @Route("/phones/{id}", name="phone_delete", methods={"DELETE"})
     */
    public function phoneDelete($id, PhoneManager $phoneManager, Request $request)
    {
        $phoneManager->delete($id);
        
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}