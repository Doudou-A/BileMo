<?php

namespace App\Controller;

use App\Link\ClientLink;
use App\Service\UserManager;
use App\Service\ClientManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
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
    public function clientModify(ClientManager $clientManager, ClientLink $clientlink, UserManager $userManager, Request $request)
    {
        $user = $this->getUser();

        $email = $request->query->get('email');

        $client = $userManager->verify($user, $email);

        if ($client === null)
        {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }

        $client = $clientManager->modify($client);

        $client->setLinks($clientlink->getlinks());

        return $clientManager->responseDetail($client);
    }
}