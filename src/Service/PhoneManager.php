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
    private $manager;
    private $request;
    private $repo;
    private $serializer;

    public function __construct(ClientManager $clientManager, EntityManagerInterface $manager, PhoneRepository $repo, Request $request, SerializerInterface $serializer)
    {
        $this->clientManager = $clientManager;
        $this->manager = $manager;
        $this->request = $request;
        $this->repo = $repo;
        $this->serializer = $serializer;
    }

    public function add()
    {
        $phone = $this->getData();

        $phone->setDateCreated(new \DateTime());
        $phone->setAvailability(true);

        $this->persist($phone);

        return $phone;
    }

    public function avaibility($data)
    {
        $phone = $this->getPhone($data);

        $availability = $phone->getAvailability();

        if ($availability == false) {
            exit(new Response('Ce téléphone n\'est pas disponible !', Response::HTTP_UNAUTHORIZED));
        }
    }

    public function delete()
    {
        $data = $this->getData();
        $phone = $this->getPhone($data);

        $client = $phone->getClient();

        $this->clientManager->decrement($client);

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

    public function modify()
    {
        $data = $this->getData();
        $phone = $this->getPhone();

        $name = $data->getName();
        $content = $data->getContent();

        if ($name != null) {
            $phone->setName($name);
        }
        if ($content != null) {
            $phone->setContent($content);
        }

        $this->persist($phone);

        return $phone;
    }

    public function pagination($page)
    {
        $nbPhonesParPage = 15;

        $phones = $this->repo->findAllPagineEtTrie($page, $nbPhonesParPage);

        return $phones;
    }

    public function persist($entity)
    {
        $this->manager->persist($entity);
        $this->manager->flush();
    }

    public function relationAdd($client)
    {
        $phone = $this->getPhone();

        $this->avaibility($phone);

        $phone->setAvailability(false);
        $phone->setClient($client);

        $this->clientManager->increment($client);

        $this->persist($phone);
        $this->persist($client);

        return $phone;
    }

    public function relationDelete()
    {
        $phone = $this->getPhone();

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

    public function responseDetail($phone)
    {
        $data = $this->serializer->serialize($phone, 'json', ['groups' => 'detail']);

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function responseList($phone)
    {
        $data = $this->serializer->serialize($phone, 'json', ['groups' => 'list']);

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
