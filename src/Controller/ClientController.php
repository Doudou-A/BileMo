<?php

namespace App\Controller;

use App\Service\Message;
use App\Service\UserManager;
use App\Service\PhoneManager;
use App\Service\ClientManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController implements TokenAuthenticatedController
{
    
    /**
     * @ROUTE("/client", name="add_client", methods={"POST"})
     */
    public function addClient(ClientManager $clientManager, SerializerInterface $serializer, Message $message)
    {
        $user = $this->getUser();

        $client = $clientManager->add($user);
        
        $data = $serializer->serialize($client, 'json', ['groups' => 'detail']);

        return $clientManager->responseGroups($data);
    }

    /**
     * @Route("/client", name="client_modify", methods={"PUT"})
     */
    public function clientModify(ClientManager $clientManager, UserManager $userManager, Message $message)
    {
        $user = $this->getUser();

        $client = $userManager->verify($user);

        $client = $clientManager->modify($client);

        return $clientManager->response($client);
    }

    /**
     * @ROUTE("/client", name="delete-client", methods={"DELETE"})
     */
    public function deleteClient(ClientManager $clientManager, PhoneManager $phoneManager, UserManager $userManager, Message $message)
    {
        $user = $this->getUser();

        $client = $userManager->verify($user);

        $phones = $phoneManager->findByClient($client);

        foreach ($phones as $phone) {
           $phoneManager->deleteClient($phone);
        }

        $clientManager->remove($client);
    
        return $message->removeSuccess();
    }

    /**
     * @Route("/client/{page}", name="client_all", methods={"GET"})
     */
    public function showClientAll(ClientManager $clientManager, $page,  SerializerInterface $serializer)
    {
        $user = $this->getUser()->getId();

        $clients = $clientManager->pagination($page, $user);
  
        $data = $serializer->serialize($clients, 'json', ['groups' => 'list']);

        return $clientManager->responseGroups($data);
    }

    /**
     * @Route("/client", name="client_show", methods={"GET"})
     */
    public function showClientAction(ClientManager $clientManager, UserManager $userManager, SerializerInterface $serializer)
    {
        $user = $this->getUser();

        $client = $userManager->verify($user);

        $data = $serializer->serialize($client, 'json', ['groups' => 'detail']);

        return $clientManager->responseGroups($data);
    }
}
