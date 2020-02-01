<?php

namespace App\Controller;

use App\Service\Message;
use App\Service\UserManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserDeleteController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @ROUTE("/user", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser(UserManager $userManager)
    {
        $user = $userManager->getUser();

        $userManager->delete($user);

        return new Response(Response::HTTP_OK);
    }
}
