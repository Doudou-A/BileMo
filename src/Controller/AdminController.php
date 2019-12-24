<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Token;
use App\Service\Content;
use App\Service\Message;
use App\Service\Persist;
use App\Service\AddEntity;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    
    /**
     * @ROUTE("admin/add-user/{token}", name="add_user")
     */
    public function addUser($token, Token $tokenVerify, AddEntity $addEntity, Content $content, Persist $persist, Message $message)
    {
        $tokenVerify->verify($token);

        $data = $content->getData('user');

        $user = $addEntity->setData($data);

        $persist->persistEntity($user);

        return $message->addSuccess();
    }

    /**
     * @ROUTE("admin/delete-user/{id}/{token}", name="delete-user")
     */
    public function deleteUser(User $user, $token, Token $tokenVerify, AddEntity $addEntity, Content $content, Persist $persist, Message $message)
    {
        $tokenVerify->verify($token);

        $persist->remove($user);
        
        return $message->removeSuccess();
    }

}
