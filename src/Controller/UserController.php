<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Token;
use App\Service\Content;
use App\Service\Manager;
use App\Service\Message;
use App\Service\AddEntity;
use App\Service\UserManager;
use App\Repository\PhoneRepository;
use App\Repository\ClientRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    /**
     * @ROUTE("admin/user", name="add_user", methods={"POST"})
     */
    public function addUser(UserManager $userManager)
    {
        $data = $userManager->getData();

        $user = $userManager->add($data);

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
