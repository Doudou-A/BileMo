<?php

namespace App\Service;

use App\Entity\Client;
use App\Service\PhoneManager;
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

    public function add($client, $user)
    {
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

    public function remove($entity)
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }

    public function response($entity)
    {
        $data = $this->serialize($entity, 'json');

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function responseGroups($data)
    {
        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function serialize($client)
    {
        $data = $this->serializer->serialize($client, 'json');

        return $data;
    }
}
