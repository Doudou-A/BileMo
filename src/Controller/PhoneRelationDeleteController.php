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
    public function relationDelete(PhoneManager $phoneManager, UserManager $userManager, Request $request)
    {
        $user = $this->getUser();

        $email = $request->query->get('email');

        $verify = $userManager->verify($user, $email);

        if ($verify === null)
        {
            new Response(null, Response::HTTP_FORBIDDEN);
        }

        $serialNumber = $request->query->get('serialNumber');

        $phone = $phoneManager->relationDelete($serialNumber);

        return $phoneManager->responseDetail($phone);
    }
}