<?php

namespace App\Controller;

use App\Link\PhoneLink;
use App\Service\PhoneManager;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneShowController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @Route("/phone", name="phone_show", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show a phone",
     * )
     * @SWG\Parameter(
     *     name="serialNumber",
     *     in="query",
     *     type="string",
     *     description="serialNumber of the phone than you want to show informations"
     * )
     */
    public function PhoneShow(PhoneManager $phoneManager, PhoneLink $phonelink)
    {

        $phone = $phoneManager->getPhone();

        $phone->setLinks($phonelink->getlinks());

        return $phoneManager->responseDetail($phone); 
    }
}
