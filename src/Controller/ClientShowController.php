<?php

namespace App\Controller;

use App\Service\UserManager;
use App\Service\ClientManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientShowController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @Route("/client", name="client_show", methods={"GET"})
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
    public function ClientShow(ClientManager $clientManager, UserManager $userManager, Response $response)
    {
        $user = $this->getUser();

        $client = $userManager->verify($user);

        if ($client == null)
        {
            new Response('Vous n\'êtes pas autorisé à faire cette action !', Response::HTTP_FORBIDDEN);
        }

        $response->setEtag(md5($response->getContent()));
        $response->setPublic();
        $response->isNotModified($request);
        
        return $clientManager->responseDetail($client);
    }
}
