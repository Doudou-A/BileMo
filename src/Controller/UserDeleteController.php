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
     * @ROUTE("/user", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser(UserManager $userManager, Request $request)
    {
        $username = $request->query->get('username');

        $user = $userManager->getUser($username);

        $userManager->delete($user);

        return new Response(Response::HTTP_NO_CONTENT);
    }
}
