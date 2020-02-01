<?php

namespace App\Controller;

use App\Service\UserManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserCreateController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @ROUTE("/user", name="add_user", methods={"POST"})
     */
    public function addUser(UserManager $userManager)
    {
        $user = $userManager->add();

        return $userManager->response($user);
    }
}