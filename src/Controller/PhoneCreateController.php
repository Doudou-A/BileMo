<?php

namespace App\Controller;

use App\Service\PhoneManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneCreateController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @Route("/phone", name="phone_create", methods={"POST"})
     */
    public function phoneCreate(PhoneManager $phoneManager)
    {
        $phone = $phoneManager->add();
        
        return $phoneManager->responseDetail($phone);
    }
}