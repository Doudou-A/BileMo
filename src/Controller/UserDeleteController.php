<?php

namespace App\Controller;

use App\Service\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserDeleteController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @ROUTE("/users/{id}", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser($id, UserManager $userManager, Request $request)
    {
        $user = $userManager->getUser($id);

        $userManager->delete($user);

        return new Response(Response::HTTP_NO_CONTENT);
    }
}
