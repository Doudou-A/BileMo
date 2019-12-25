<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Token;
use App\Service\Content;
use App\Service\Manager;
use App\Service\Message;
use App\Service\AddEntity;
use App\Repository\ClientRepository;
use App\Repository\PhoneRepository;
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
     * @ROUTE("admin/delete-user/{username}/{token}", name="delete-user")
     */
    public function deleteUser(User $user, $token, Token $tokenVerify, Manager $manager, Message $message, ClientRepository $repoClient, PhoneRepository $repoPhone)
    {
        $tokenVerify->verify($token);

        $clients = $repoClient->findByUser($user);

        foreach ($clients as $client) {

            $phones = $repoPhone->findByClient($client);

            foreach ($phones as $phone) {
                $phone->setAvailability(true);
                $phone->setClient(null);
                $manager->persist($phone);
            }
        }

        $manager->remove($user);

        return $message->removeSuccess();
    }
}
