<?php

namespace App\Controller;

use App\Entity\User;
use Firebase\JWT\JWT;
use App\Service\Token;
use App\Service\AddUser;
use App\Service\Content;
use App\Service\Message;
use App\Service\Persist;
use App\Service\AddEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /**
     * @ROUTE("add-client/{token}", name="add_client")
     */
    public function addClient($token, Token $tokenVerify, Content $content, Persist $persist, Message $message)
    {
        $tokenVerify->verify($token);

        $client = $content->getData('client');

        $client->setDateCreated(new \DateTime());

        $persist->persistEntity($client);

        return $message->addSuccess();
    }

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
     * @Route("/login", name="security_login")
     */
    public function login(Request $request)
    {
        $user = $this->getUser();

        $key = "token";
        $payload = array(
            'Id' => $user->getId(),
            'FirstName' => $user->getFirstName(),
            'Name' => $user->getName(),
            'Username' => $user->getUsername()
        );

        $jwt = JWT::encode($payload, $key);

        return $this->json([
            'firstName' => $user->getFirstName(),
            'Name' => $user->getName(),
            'DateCreated' => $user->getDateCreated(),
            'Username' => $user->getUsername(),
            'Token' => $jwt,
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        throw new \Exception('This sould never be reached!');
    }

    /**
     * @Route("/logout-message", name="logout-message")
     */
    public function logoutMessage()
    {
        return new Response('Vous avez été déconnecté !', Response::HTTP_CREATED);
    }
}
