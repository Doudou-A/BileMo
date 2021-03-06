<?php

namespace App\Service;

use App\Entity\User;
use App\Service\ClientManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $clientManager;
    private $encoder;
    private $manager;
    private $phoneManager;
    private $repo;
    private $request;
    private $serializer;

    public function __construct(ClientManager $clientManager, EntityManagerInterface $manager, PhoneManager $phoneManager, UserRepository $repo, UserPasswordEncoderInterface $encoder, Request $request, SerializerInterface $serializer)
    {
        $this->phoneManager = $phoneManager;
        $this->clientManager = $clientManager;
        $this->encoder = $encoder;
        $this->request = $request;
        $this->repo = $repo;
        $this->serializer = $serializer;
        $this->manager = $manager;
    }

    public function add()
    {
        $user = $this->getData();

        $hash = $this->encoder->encodePassword($user, $user->getPassword());

        $user->setDateCreated(new \DateTime());
        $user->setPassword($hash);
        
        $this->persist($user);

        return $user;
    }

    public function delete($user)
    {
        $clients = $this->clientManager->findByUser($user);

        foreach ($clients as $client) {

            $phones = $this->phoneManager->findByClient($client);

            foreach ($phones as $phone) {
                $this->phoneManager->deleteClient($phone);
            }
        }

        $this->remove($user);
    }

    public function getData()
    {
        $data = $this->request->getContent();
        
        $data = $this->serializer->deserialize($data, User::class, 'json');

        return $data;
    }

    public function getUser($id)
    {
        $user = $this->repo->findById($id);

        return $user[0];
    }

    public function modify($id)
    {
        $data = $this->getData();
        $user = $this->getUser($id);

        $name = $data->getName();
        $firstName = $data->getFirstName();
        $password = $data->getPassword();

        if ($name != null) {
            $user->setName($name);
        } 
        if ($firstName != null) {
            $user->setFirstName($firstName);
        } 
        if ($password != null) {
            $hash = $this->encoder->encodePassword($user, $password);
            $user->setPassword($hash);
        }

        $this->persist($user);

        return $user;
    }

    public function persist($entity)
    {
        $this->manager->persist($entity);
        $this->manager->flush();
    }

    public function response($user)
    {
        $data = $this->serialize($user, 'json');

        $response = new Response($data, Response::HTTP_OK);

        $response->headers->set('Content-Type', 'application/json');
        $response->setEtag(md5($response->getContent()));
        $response->setPublic();
        $response->isNotModified($this->request);

        return $response;
    }

    public function remove($entity)
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }

    public function serialize($user)
    {
        $data = $this->serializer->serialize($user, 'json', ['groups' => 'view']);

        return $data;
    }

    public function verify($userCo, $id)
    {
        $client = $this->clientManager->getClient($id);

        $user = $client->getUser();

        if($user != $userCo)
        {
            $client = null;

            return $client;
        } 

        return $client;
    }

    public function verifyUser($userCo, $id)
    {
        $user = $this->getUser($id);

        if($user != $userCo)
        {
            $user = null;

            return $user;
        } 

        return $user;
    }
}

