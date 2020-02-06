<?php

namespace App\Controller;

use App\Service\UserManager;
use App\Service\PhoneManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneRelationDeleteController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @Route("/client/phone", name="relation_delete",  methods={"DELETE"})
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
    public function relationDelete(PhoneManager $phoneManager, UserManager $userManager)
    {
        $user = $this->getUser();

        $verify = $userManager->verify($user);

        if ($verify == null)
        {
            new Response(null, Response::HTTP_FORBIDDEN);
        }

        $phone = $phoneManager->relationDelete();

        return $phoneManager->responseDetail($phone);
    }
}