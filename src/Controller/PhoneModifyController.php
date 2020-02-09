<?php

namespace App\Controller;

use App\Service\PhoneManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneModifyController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @Route("/phone", name="phone_modify", methods={"PUT"})
     */
    public function phoneModify(PhoneManager $phoneManager, Request $request)
    {
        $serialNumber = $request->query->get('serialNumber');

        $phone = $phoneManager->modify($serialNumber);

        return $phoneManager->responseDetail($phone);
    }
}