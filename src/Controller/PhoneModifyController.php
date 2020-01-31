<?php

namespace App\Controller;

use App\Service\PhoneManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneModifyController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @Route("/phone", name="phone_modify", methods={"PUT"})
     */
    public function phoneModify(PhoneManager $phoneManager)
    {
        $phone = $phoneManager->modify();

        return $phoneManager->responseDetail($phone);
    }
}