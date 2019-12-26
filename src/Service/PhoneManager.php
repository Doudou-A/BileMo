<?php

namespace App\Service;

use App\Entity\Phone;
use App\Service\ClientManager;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class PhoneManager
{
    private $clientManager;
    private $request;
    private $serializer;
    private $manager;
    private $repo;

    public function __construct(ClientManager $clientManager, EntityManagerInterface $manager, PhoneRepository $repo, Request $request, SerializerInterface $serializer)
    {
        $this->clientManager = $clientManager;
        $this->request = $request;
        $this->repo = $repo;
        $this->serializer = $serializer;
        $this->manager = $manager;
    }

    public function add($phone)
    {
        $phone->setDateCreated(new \DateTime());
        $phone->setAvailability(true);

        $this->persist($phone);

        return $phone;
    }

    public function avaibility($data)
    {
        $phone = $this->getPhone($data);

        $availability = $phone->getAvailability();

        return $availability;
    }

    public function delete($data)
    {
        $phone = $this->getPhone($data);

        $this->remove($phone);
    }

    public function deleteClient($phone)
    {
        $phone->setAvailability(true);
        $phone->setClient(null);

        $this->persist($phone);
    }

    public function findByClient($client)
    {
        $phones = $this->repo->findByClient($client);

        return $phones;
    }

    public function getData()
    {
        $data = $this->request->getContent();

        $data = $this->serializer->deserialize($data, Phone::class, 'json');

        return $data;
    }

    public function getPhone()
    {
        $data = $this->getData();

        $serialNumber = $data->getSerialNumber();

        $phone = $this->repo->findBySerialNumber($serialNumber);

        return $phone[0];
    }

    public function modify($data)
    {
        $phone = $this->getPhone();

        $phone->setName($data->getName());
        $phone->setContent($data->getContent());

        $this->persist($phone);

        return $phone;
    }

    public function pagination($page)
    {
        $nbPhonesParPage = 5;

        $phones = $this->repo->findAllPagineEtTrie($page, $nbPhonesParPage);

        return $phones;
    }

    public function persist($entity)
    {
        $this->manager->persist($entity);
        $this->manager->flush();
    }

    public function relationAdd($phone, $client)
    {
        $phone->setAvailability(false);
        $phone->setClient($client);

        $this->clientManager->increment($client);

        $this->persist($phone);

        return $phone;
    }

    public function relationDelete($phone)
    {
        $client = $phone->getClient();

        $phone->setAvailability(true);
        $phone->setClient(null);

        $this->clientManager->decrement($client);

        $this->persist($phone);

        return $phone;
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
    
    public function serialize($phone)
    {
        $data = $this->serializer->serialize($phone, 'json');

        return $data;
    }

}
