<?php

namespace App\Controller;

use App\Service\UserManager;
use App\Service\PhoneManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneRelationDeleteController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @Route("/clients/{clientId}/phones/{phoneId}", name="relation_delete",  methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a relation beetween your client and a phone. You can create several ralation with one client",
     * )
     * @SWG\Parameter(
     *     name="Id phone",
     *     in="query",
     *     type="integer"
     * )
     * @SWG\Parameter(
     *     name="Id phone",
     *     in="query",
     *     type="integer"
     * )
     */
    public function relationDelete($clientId, $phoneId, PhoneManager $phoneManager, UserManager $userManager, Request $request)
    {
        $user = $this->getUser();

        $verify = $userManager->verify($user, $clientId);

        if ($verify === null)
        {
            new Response(null, Response::HTTP_FORBIDDEN);
        }

        $phone = $phoneManager->relationDelete($phoneId);

        return $phoneManager->responseDetail($phone);
    }
}