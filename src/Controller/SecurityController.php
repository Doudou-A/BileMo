<?php

namespace App\Controller;

use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{

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
