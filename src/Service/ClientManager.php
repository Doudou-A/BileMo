<?php

namespace App\Service;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ClientManager
{
    private $manager;
    private $request;
    private $repo;
    private $serializer;

    public function __construct(ClientRepository $repo, EntityManagerInterface $manager, Request $request, SerializerInterface $serializer)
    {
        $this->manager = $manager;
        $this->request = $request;
        $this->repo = $repo;
        $this->serializer = $serializer;
    }

    public function add($user)
    {
        $client = $this->getData();

        $client->setDateCreated(new \DateTime());
        $client->setNumberOfPhone(0);
        $client->setUser($user);

        $this->persist($client);

        return $client;
    }

    public function getData()
    {
        $data = $this->request->getContent();

        $data = $this->serializer->deserialize($data, Client::class, 'json');

        return $data;
    }

    public function increment($client)
    {
        $count = $client->getNumberOfPhone();
        $client->setNumberOfPhone(++$count);

        $this->persist($client);
    }

    public function decrement($client)
    {
        $count = $client->getNumberOfPhone();
        $client->setNumberOfPhone(--$count);

        $this->persist($client);
    }

    public function findByUser($user)
    {
        $clients = $this->repo->findByUser($user);

        return $clients;
    }

    public function getClient()
    {
        $data = $this->getData();

        $email = $data->getEmail();

        $client = $this->repo->findByEmail($email);

        return $client[0];
    }

    public function pagination($page, $user)
    {
        $nbPhonesParPage = 5;

        $phones = $this->repo->findAllPagineEtTrie($page, $nbPhonesParPage, $user);

        return $phones;
    }

    public function persist($entity)
    {
        $this->manager->persist($entity);
        $this->manager->flush();
    }

    public function modify($client)
    {
        $data = $this->getData();

        $name = $data->getName();
        $firstName = $data->getFirstName();

        if ($name != null) {
            $client->setName($name);
        } 
        if ($firstName != null) {
            $client->setFirstName($firstName);
        }

        $this->persist($client);

        return $client;
    }

    public function remove($entity)
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }

    public function responseDetail($client)
    {
        $data = $this->serializer->serialize($client, 'json', ['groups' => 'detail']);

        $response = $this->response($data);

        return $response;
    }

    public function responseList($client)
    {
        $data = $this->serializer->serialize($client, 'json', ['groups' => 'list']);

        $response = $this->response($data);

        return $response;
    }

    public function response($data)
    {
        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');
        $response->setEtag(md5($response->getContent()));
        $response->setPublic();
        $response->isNotModified($this->request);

        return $response;
    }
}
