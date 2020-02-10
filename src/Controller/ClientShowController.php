<?php

namespace App\Controller;

use App\Entity\Client;
use App\Link\ClientLink;
use App\Service\UserManager;
use App\Service\ClientManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientShowController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @Route("/clients/{id}", name="client_show", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show a client",
     * )
     * @SWG\Parameter(
     *     name="Email",
     *     in="query",
     *     type="string",
     *     description="Email of the client than you want to show informations"
     * )
     */
    public function ClientShow($id, ClientManager $clientManager, ClientLink $clientlink, Request $request, UserManager $userManager)
    {
        $user = $this->getUser();

        $client = $userManager->verify($user, $id);

        if ($client === null)
        {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }

        $client->setLinks($clientlink->getlinks());

        return $clientManager->responseDetail($client);
    }
}
