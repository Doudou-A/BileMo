<?php

namespace App\Controller;

use App\Entity\Client;
use App\Service\Token;
use App\Service\Content;
use App\Service\Manager;
use App\Service\Message;
use App\Service\AddEntity;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    
    /**
     * @ROUTE("add-client/{token}", name="add_client")
     */
    public function addClient($token, Token $tokenVerify, Content $content, Manager $manager, Message $message)
    {
        $tokenVerify->verify($token);

        $client = $content->getData('client');

        $client->setDateCreated(new \DateTime());
        $user = $this->getUser();

        $client->setUser($user);

        $manager->persist($client);

        return $message->addSuccess();
    }

    /**
     * @ROUTE("delete-client/{email}/{token}", name="delete-client")
     */
    public function deleteClient(Client $client, $token, Token $tokenVerify, AddEntity $addEntity, Content $content, Manager $manager, Message $message)
    {
        $tokenVerify->verify($token);

        $manager->remove($client);
        
        return $message->removeSuccess();
    }

    /**
     * @Route("/clients/all/{page}/{token}", name="client_all" )
     */
    public function showClientAll($token, Token $tokenVerify, SerializerInterface $serializer, ClientRepository $repo, $page)
    {
        $tokenVerify->verify($token);

        $nbClientsParPage = 1;

        $clients = $repo->findAll();

        $clients = $repo->findAllPagineEtTrie($page, $nbClientsParPage);

        $data = $serializer->serialize($clients, 'json', ['groups' => 'list']);

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
