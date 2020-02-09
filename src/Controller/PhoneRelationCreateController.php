<?php

namespace App\Controller;

use App\Link\ClientLink;
use App\Service\UserManager;
use App\Service\PhoneManager;
use App\Service\ClientManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneRelationCreateController extends AbstractController implements TokenAuthenticatedController
{

   /**
     * @Route("/client/phone",  name="relation_create", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a relation beetween your client and a phone. You can create several relation with one client",
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
    public function relationCreate(PhoneManager $phoneManager, ClientLink $clientlink, UserManager $userManager, ClientManager $clientManager, Request $request)
    {
        $user = $this->getUser();

        $email = $request->query->get('email');

        $client = $userManager->verify($user, $email);

        if ($client === null)
        {
            return new Response(Response::HTTP_UNAUTHORIZED);
        }

        $serialNumber = $request->query->get('serialNumber');

        $phoneManager->relationAdd($client, $serialNumber);

        $client->setLinks($clientlink->getlinks());

        return $clientManager->responseDetail($client)
                ->setStatusCode(Response::HTTP_CREATED);
    }
}