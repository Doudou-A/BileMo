<?php

namespace App\Controller;

use App\Link\PhoneLink;
use App\Service\PhoneManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneShowController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @Route("/phones/{id}", name="phone_show", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show a phone",
     * )
     * @SWG\Parameter(
     *     name="Id",
     *     in="query",
     *     type="string",
     *     description="Id of the phone than you want to show informations"
     * )
     */
    public function PhoneShow($id, PhoneManager $phoneManager, Request $request, PhoneLink $phonelink)
    {
        $phone = $phoneManager->getPhone($id);

        $phone->setLinks($phonelink->getlinks());

        return $phoneManager->responseDetail($phone); 
    }
}
