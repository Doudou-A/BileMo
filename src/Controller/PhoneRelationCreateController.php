<?php

namespace App\Controller;

use App\Service\UserManager;
use App\Service\PhoneManager;
use App\Service\ClientManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneRelationCreateController extends AbstractController implements TokenAuthenticatedController
{

   /**
     * @Route("/phone/client",  name="relation_create", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a relation beetween your client and a phone. You can create several ralation with one client",
     * )
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="serialNumber",
     *     in="query",
     *     type="integer"
     * )
     */
    public function relationCreate(PhoneManager $phoneManager, UserManager $userManager, ClientManager $clientManager)
    {

        $user = $this->getUser();

        $client = $userManager->verify($user);

        if ($client == null)
        {
            new Response('Vous n\'êtes pas autorisé à faire cette action !', Response::HTTP_FORBIDDEN);
        }

        $phoneManager->relationAdd($client);

        return $clientManager->responseList($client);
    }
}