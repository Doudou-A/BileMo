<?php

namespace App\Controller;

use App\Entity\Client;
use App\Service\Token;
use App\Service\Content;
use App\Service\Manager;
use App\Service\Message;
use App\Repository\ClientRepository;
use App\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    
    /**
     * @ROUTE("/client/add-client/{token}", name="add_client")
     */
    public function addClient($token, Token $tokenVerify, Content $content, Manager $manager, Message $message)
    {
        $tokenVerify->verify($token);

        $client = $content->getData('client');

        $client->setDateCreated(new \DateTime());
        $client->setNumberOfPhone(0);
        $user = $this->getUser();

        $client->setUser($user);

        $manager->persist($client);

        return $message->addSuccess();
    }

    /**
     * @ROUTE("/client/delete-client/{email}/{token}", name="delete-client")
     */
    public function deleteClient(Client $client, $token, Token $tokenVerify, Manager $manager, Message $message, PhoneRepository $repo)
    {
        $tokenVerify->verify($token);

        $user = $this->getUser();
        $userClient = $client->getUser();

        if($user != $userClient)
        {
            return $message->RemoveDenied();
            die;
        }
        
        $phones = $repo->findByClient($client);

        foreach ($phones as $phone) {
            $phone->setAvailability(true);
            $phone->setClient(null);
            $manager->persist($phone);
        }

        $manager->remove($client);
    
        return $message->removeSuccess();
    }

    /**
     * @Route("/client/all/{page}/{token}", name="client_all" )
     */
    public function showClientAll($token, Token $tokenVerify, SerializerInterface $serializer, ClientRepository $repo, $page)
    {
        $tokenVerify->verify($token);

        $user = $this->getUser()->getId();

        $nbClientsParPage = 5;

        $clients = $repo->findAllPagineEtTrie($page, $nbClientsParPage, $user);
  
        $data = $serializer->serialize($clients, 'json', ['groups' => 'list']);

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/client/{email}/{token}", name="client_show")
     */
    public function showClientAction($token, Token $tokenVerify, Client $client, SerializerInterface $serializer)
    {
        $tokenVerify->verify($token);

        $data = $serializer->serialize($client, 'json', ['groups' => 'detail']);

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
