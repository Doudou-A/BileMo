<?php

namespace App\Controller;

use App\Service\Message;
use App\Service\UserManager;
use App\Service\PhoneManager;
use App\Service\ClientManager;
use App\Repository\PhoneRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController implements TokenAuthenticatedController
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
    public function phoneModify(PhoneManager $phoneManager, SerializerInterface $serializer)
    {
        $phone = $phoneManager->modify();

        return $phoneManager->responseGroups($phone, 'detail');
    }

    /**
     * @Route("/relation",  name="relation_create", methods={"POST"})
     */
    public function relationCreate(PhoneManager $phoneManager, ClientManager $clientManager, UserManager $userManager, Message $message)
    {

        $user = $this->getUser();

        $client = $userManager->verify($user);

        $phoneManager->relationAdd($client);

        return $message->modifySuccess();
    }

    /**
     * @Route("/relation", name="relation_delete",  methods={"DELETE"})
     */
    public function relationDelete(PhoneManager $phoneManager, UserManager $userManager, Message $message)
    {
        $user = $this->getUser();

        $userManager->verify($user);

        $phone = $phoneManager->relationDelete();

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
