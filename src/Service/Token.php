<?php

namespace App\Service;

use Firebase\JWT\JWT;
use App\Repository\UserRepository;
use App\Repository\AdminRepository;
use Symfony\Component\HttpFoundation\Response;

class Token
{
    private $repoAdmin;
    private $repoUser;

    public function __construct(AdminRepository $repoAdmin, UserRepository $repoUser)
    {
        $this->repoAdmin = $repoAdmin;
        $this->repoUser = $repoUser;
    }

    public function verify($token)
    {
        $key = "token";
        $decoded = JWT::decode($token, $key, array('HS256'));

        $decoded_array = (array) $decoded;

        $id = $decoded_array['Id'];

        $entity = $this->repoAdmin->find($id);
        if($entity == null){
            $entity = $this->repoUser->find($id);
        }

        $array =array(
            'Id' => $entity->getId(),
            'FirstName' => $entity->getFirstName(),
            'Name' => $entity->getName(),
            'Username' => $entity->getUsername()
        );

        if($decoded_array != $array)
        {
            echo('Le token que vous avez inséré n\'est pas correct !');
            die;
        }
    }
}