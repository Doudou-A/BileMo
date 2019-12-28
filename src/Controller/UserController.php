<?php

namespace App\Controller;

use App\Service\Message;
use App\Service\UserManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @ROUTE("admin/user", name="add_user", methods={"POST"})
     */
    public function addUser(UserManager $userManager)
    {
        $user = $userManager->add();

        return $userManager->response($user);
    }

    /**
     * @ROUTE("user", name="add_user", methods={"PUT"})
     */
    public function modifyUser(UserManager $userManager, Message $message)
    {
        $userCo = $this->getUser();

        $verify = $userManager->verify($userCo);

        if($verify == false){
            return $message->noAccess();
        }

        $user = $userManager->modify();

        return $userManager->response($user);
    }

    /**
     * @ROUTE("admin/user", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser(UserManager $userManager, Message $message)
    {
        $user = $userManager->getUser();

        $userManager->delete($user);

        return $message->removeSuccess();
    }
}
