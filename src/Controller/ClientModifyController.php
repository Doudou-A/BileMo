<?php

namespace App\Controller;

use App\Service\UserManager;
use App\Service\ClientManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientModifyController extends AbstractController implements TokenAuthenticatedController
{
    
    /**
     * @Route("/client", name="client_modify", methods={"PUT"})
     * * @SWG\Response(
     *     response=200,
     *     description="Modify the name and/or the firstName. You can't change the email but you must to post him.",
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="firstName",
     *     in="query",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="Email",
     *     in="query",
     *     type="string",
     *     description="Email of the client than you want to modify"
     * )
     */
    public function clientModify(ClientManager $clientManager, UserManager $userManager)
    {
        $user = $this->getUser();

        $client = $userManager->verify($user);

        if ($client == null)
        {
            new Response('Vous n\'êtes pas autorisé à faire cette action !', Response::HTTP_FORBIDDEN);
        }

        $client = $clientManager->modify($client);

        return $clientManager->responseDetail($client);
    }
}