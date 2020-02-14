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
     * @Route("/clients", name="client_list", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show all of your clients",
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer"
     * )
     */
    public function ClientList(ClientManager $clientManager, Request $request)
    {
        $user = $this->getUser()->getId();

        $page = $request->query->get('page');

        $clients = $clientManager->pagination($page, $user);

        return $clientManager->responseList($clients);
    }
}
