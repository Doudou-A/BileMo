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
     * @Route("/phone", name="phone_delete", methods={"DELETE"})
     */
    public function phoneDelete(PhoneManager $phoneManager, Request $request)
    {
        $serialNumber = $request->query->get('serialNumber');

        $phoneManager->delete($serialNumber);
        
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}