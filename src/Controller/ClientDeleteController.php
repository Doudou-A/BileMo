<?php

namespace App\Controller;

use App\Service\Message;
use App\Service\UserManager;
use App\Service\PhoneManager;
use App\Service\ClientManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientDeleteController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @ROUTE("/client", name="delete-client", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Delete a client",
     * )
     * @SWG\Parameter(
     *     name="Email",
     *     in="query",
     *     type="string",
     *     description=""
     * )
     */
    public function clientDelete(ClientManager $clientManager, PhoneManager $phoneManager, UserManager $userManager, Message $message)
    {
        $user = $this->getUser();

        $client = $userManager->verify($user);

        if ($client == null)
        {
            new Response('Vous n\'êtes pas autorisé à faire cette action !', Response::HTTP_FORBIDDEN);
        }

        $phones = $phoneManager->findByClient($client);

        foreach ($phones as $phone) {
           $phoneManager->deleteClient($phone);
        }

        $clientManager->remove($client);
    
        return new Response(Response::HTTP_OK);
    }
}