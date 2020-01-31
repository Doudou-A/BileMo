<?php

namespace App\Controller;

use App\Service\PhoneManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneDeleteController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @Route("/phone", name="phone_delete", methods={"DELETE"})
     */
    public function phoneDelete(PhoneManager $phoneManager)
    {
        $phoneManager->delete();
        
        return new Response(Response::HTTP_OK);
    }
}