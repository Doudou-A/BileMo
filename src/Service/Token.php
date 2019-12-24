<?php

namespace App\Service;

use Firebase\JWT\JWT;
use App\Repository\AdminRepository;
use Symfony\Component\HttpFoundation\Response;

class Token
{
    private $repo;

    public function __construct(AdminRepository $repo)
    {
        $this->repo = $repo;
    }

    public function verify($token)
    {
        $key = "token";
        $decoded = JWT::decode($token, $key, array('HS256'));

        $decoded_array = (array) $decoded;

        $id = $decoded_array['Id'];

        $admin = $this->repo->find($id);
        $array =array(
            'Id' => $admin->getId(),
            'FirstName' => $admin->getFirstName(),
            'Name' => $admin->getName(),
            'Username' => $admin->getUsername()
        );

        if($decoded_array != $array)
        {
            echo('Le token que vous avez inséré n\'est pas correct !');
            die;
        }
    }
}