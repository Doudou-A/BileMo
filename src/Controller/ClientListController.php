<?php

namespace App\Controller;

use App\Service\ClientManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientListController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @Route("/clients", name="client_all", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show all of your clients",
     * )
     * @SWG\Parameter(
     *     name="page on URL",
     *     in="query",
     *     type="integer"
     * )
     */
    public function ClientList(ClientManager $clientManager, Request $request)
    {
        $page = $request->query->get('page');

        $user = $this->getUser()->getId();

        $clients = $clientManager->pagination($page, $user);

        return $clientManager->responseList($clients);
    }
}