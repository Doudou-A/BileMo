<?php

namespace App\Controller;

use App\Service\UserManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserModifyController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @ROUTE("/users/{id}", name="modify_user", methods={"PUT"})
     * @SWG\Response(
     *     response=200,
     *     description="Modify your information about your account. You can change your name and/or firstName and/or password",
     * )
     * @SWG\Parameter(
     *     name="Id",
     *     in="query",
     *     type="integer"
     * )
     * @SWG\Parameter(
     *     name="firstName",
     *     in="query",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     type="string"
     * )
     */
    public function modifyUser($id, UserManager $userManager, Request $request)
    {
        $userCo = $this->getUser();

        $user = $userManager->verifyUser($userCo, $id);
        
        if ($user === null)
        {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }

        $user = $userManager->modify($id);

        return $userManager->response($user);
    }
}