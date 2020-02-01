<?php

namespace App\Controller;

use App\Link\PhoneLink;
use App\Service\PhoneManager;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneListController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @Route("/phone/{page}", name="phone_list",  methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show all of phone available",
     * )
     * @SWG\Parameter(
     *     name="page on URL",
     *     in="query",
     *     type="integer"
     * )
     */
    public function PhoneList(PhoneManager $phoneManager, PhoneLink $phoneLink, $page)
    {
        $phones = $phoneManager->pagination($page);

        return $phoneManager->responseList($phones);
    }
}