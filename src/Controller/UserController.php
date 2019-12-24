<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Service\Token;
use App\Service\Content;
use App\Service\Manager;
use App\Service\Message;
use App\Service\Persist;
use App\Service\AddEntity;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    /**
     * @ROUTE("admin/add-user/{token}", name="add_user")
     */
    public function addUser($token, Token $tokenVerify, AddEntity $addEntity, Content $content, Manager $manager, Message $message)
    {
        $tokenVerify->verify($token);

        $data = $content->getData('user');

        $user = $addEntity->setData($data);

        $manager->persist($user);

        return $message->addSuccess();
    }

    /**
     * @ROUTE("admin/delete-user/{id}/{token}", name="delete-user")
     */
    public function deleteUser(User $user, $token, Token $tokenVerify, AddEntity $addEntity, Manager $manager, Message $message)
    {
        $tokenVerify->verify($token);

        $manager->remove($user);
        
        return $message->removeSuccess();
    }
    
}
