<?php

namespace App\Controller;

use App\Service\Message;
use App\Service\UserManager;
use App\Service\PhoneManager;
use App\Service\ClientManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    
    /**
     * @ROUTE("/client", name="add_client", methods={"POST"})
     */
    public function addClient(ClientManager $clientManager, Message $message)
    {
        $user = $this->getUser();

        $data = $clientManager->getData();

        $client = $clientManager->add($data, $user);

        return $message->removeSuccess();
    }

    /**
     * @Route("/client", name="client_modify", methods={"PUT"})
     */
    public function clientModify(ClientManager $clientManager)
    {
        $data = $clientManager->getData();

        $client = $clientManager->modify($data);

        return $clientManager->response($client);
    }

    /**
     * @ROUTE("/client", name="delete-client", methods={"DELETE"})
     */
    public function deleteClient(ClientManager $clientManager, PhoneManager $phoneManager, UserManager $userManager, Message $message)
    {
        $user = $this->getUser();

        $client = $clientManager->getClient();

        $verify = $userManager->verify($client, $user);

        if($verify == false){
            return $message->noAccess();
        }
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
    public function showClientAction(ClientManager $clientManager, UserManager $userManager, SerializerInterface $serializer, Message $message)
    {
        $user = $this->getUser();

        $client = $clientManager->getClient();

        $verify = $userManager->verify($client, $user);

        if($verify == false){
            return $message->noAccess();
        }

        $data = $serializer->serialize($client, 'json', ['groups' => 'detail']);

        return $clientManager->responseGroups($data);
    }
}
