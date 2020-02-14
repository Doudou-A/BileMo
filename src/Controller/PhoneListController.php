<?php

namespace App\Controller;

use App\Link\PhoneLink;
use App\Service\PhoneManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneListController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @Route("/phones", name="phone_list",  methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show all of phone available",
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer"
     * )
     */
    public function PhoneList(PhoneManager $phoneManager, Request $request)
    {
        $page = $request->query->get('page');

        $phones = $phoneManager->pagination($page);
        
        return $phoneManager->responseList($phones);
    }
}