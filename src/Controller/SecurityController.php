<?php

namespace App\Controller;

use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Swagger\Annotations as SWG;

class SecurityController extends AbstractController
{

    /** 
     * @Route("/login", name="security_login", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="login of your account",
     * )
     * @SWG\Parameter(
     *     name="userName",
     *     in="query",
     *     type="string",
     *     description="Username of your user account, it's unique."
     * )
     * @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     type="string"
     * )
     */
    public function login()
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
     * @Route("/logout", name="security_logout", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Logout of your account"
     * )
     */
    public function logout()
    {
        throw new \Exception('This sould never be reached!');
    }

    /**
     * @Route("/logout-message", name="logout-message" ,methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Message display after your logout",
     * )
     */
    public function logoutMessage()
    {
        return new Response('Vous avez été déconnecté !', Response::HTTP_OK);
    }
}
