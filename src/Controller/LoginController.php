<?php

namespace App\Controller;

use Firebase\JWT\JWT;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
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
}