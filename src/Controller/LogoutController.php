<?php

namespace App\Controller;

use Firebase\JWT\JWT;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LogoutController extends AbstractController
{
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
     * @Route("/message-logout", name="logout-message")
     */
    public function logoutMessage()
    {
        return new Response(null, Response::HTTP_OK);
    } 
}
