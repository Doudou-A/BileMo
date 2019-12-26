<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Entity\Client;
use App\Service\Token;
use App\Service\Content;
use App\Service\Manager;
use App\Service\Message;
use App\Service\PhoneManager;
use App\Service\PhoneService;
use App\Repository\PhoneRepository;
use App\Service\ClientManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController
{

    /**
     * @Route("/admin/phone", name="phone_create", methods={"POST"})
     */
    public function phoneCreate(PhoneManager $phoneManager)
    {
        $data = $phoneManager->getData();

        $phone = $phoneManager->add($data);
        
        return $phoneManager->response($phone);
    }

    /**
     * @Route("/admin/phone", name="phone_delete", methods={"DELETE"})
     */
    public function phoneDelete(PhoneManager $phoneManager, Message $message)
    {
        $phone = $phoneManager->getData();

        $phoneManager->delete($phone);
        
        return $message->removeSuccess();
    }

    /**
     * @Route("/admin/phone", name="phone_modify", methods={"PUT"})
     */
    public function phoneModify(PhoneManager $phoneManager)
    {
        $data = $phoneManager->getData();

        $phone = $phoneManager->modify($data);

        return $phoneManager->response($phone);
    }

    /**
     * @Route("/relation",  name="relation_create", methods={"POST"})
     */
    public function relationCreate(PhoneManager $phoneManager, ClientManager $clientManager, Message $message)
    {
        $phone = $phoneManager->getPhone();

        $client = $clientManager->getClient();

        $availability = $phoneManager->avaibility($phone);

        if($availability == false){
            return $message->noAvailable();
        }
        
        /* $newPhone = $phoneManager->addRelation($phone, $client);

        return $phoneManager->response($newPhone); */
        $phoneManager->relationAdd($phone, $client);

        return $message->modifySuccess();
    }

    /**
     * @Route("/relation", name="relation_delete",  methods={"DELETE"})
     */
    public function relationDelete(PhoneManager $phoneManager, Message $message)
    {
        /* $user = $this->getUser();
        $userClient = $client->getUser();

        if($user != $userClient)
        {
            return $message->RemoveDenied();
            die;
        } */
        $phone = $phoneManager->getPhone();

        $phoneManager->relationDelete($phone);

        return $phoneManager->response($phone);
    }

    /**
     * @Route("/phone/{page}", name="phone_get",  methods={"GET"})
     */
    public function showPhone(PhoneManager $phoneManager, $page, PhoneRepository $repo, SerializerInterface $serializer)
    {
        $phones = $phoneManager->pagination($page);

        $data = $serializer->serialize($phones, 'json', ['groups' => 'list']);

        return $phoneManager->responseGroups($data);
    }

    /**
     * @Route("/phone", name="phone_show", methods={"GET"})
     */
    public function showPhoneAction(PhoneManager $phoneManager, SerializerInterface $serializer)
    {
        $phone = $phoneManager->getPhone();

        $data = $serializer->serialize($phone, 'json', ['groups' => 'detail']);

        return $phoneManager->responseGroups($data);
    }
}
