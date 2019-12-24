<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Phone;
use Firebase\JWT\JWT;
use App\Service\Token;
use App\Service\Content;
use App\Service\Message;
use App\Service\Persist;
use App\Repository\AdminRepository;
use App\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PhoneController extends AbstractController
{

    /**
     * @Route("/admin/phones/{token}", name="phone_create")
     */
    public function createAction($token, Persist $persist, Content $content, Token $tokenVerify, Message $message)
    {
        $tokenVerify->verify($token);

        $phone = $content->getData('phone');

        $phone->setDateCreated(new \DateTime());

        $persist->persistEntity($phone);

        return $message->addSuccess();
    }

    /**
     * @Route("/admin/phones/delete/{id}/{token}", name="phone_delete")
     */
    public function Delete(Phone $phone, $token, Token $tokenVerify, Persist $persist, Message $message)
    {
        $tokenVerify->verify($token);

        $persist->remove($phone);
        
        return $message->removeSuccess();
    }

    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, UserPasswordEncoderInterface $encoder, SerializerInterface $serializer)
    {
        $admin = new Admin();
        $data = $request->getContent();
        $admin = $serializer->deserialize($data, Admin::class, 'json');


        $hash = $encoder->encodePassword($admin, $admin->getPassword());

        $admin->setUsername($admin->getUsername());
        $admin->setDateCreated(new \DateTime());
        $admin->setPassword($hash);


        $manager = $this->getDoctrine()->getManager();
        $manager->persist($admin);
        $manager->flush();

        return new Response('Ajout effectuées avec succès !', Response::HTTP_CREATED);
    }

    /**
     * @Route("/admin/phones/modify/{id}/{token}", name="phone_modify")
     */
    public function Modify(Phone $phone, $token, Token $tokenVerify,  Content $content, Persist $persist, Message $message)
    {
        $tokenVerify->verify($token);

        $phoneData = $content->getData('phone');

        $phone->setName($phoneData->getName());
        $phone->setContent($phoneData->getContent());

        $persist->persistEntity($phone);

        return $message->modifySuccess();
    }

    /**
     * @Route("/phones/all/{page}/{token}", name="phone_all")
     */
    public function showAll($token, Token $tokenVerify, SerializerInterface $serializer, PhoneRepository $repo, $page)
    {
        $tokenVerify->verify($token);

        $nbPhonesParPage = 5;

        $phones = $repo->findAll();

        $phones = $repo->findAllPagineEtTrie($page, $nbPhonesParPage);

        $data = $serializer->serialize($phones, 'json', ['groups' => 'list']);

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/phones/{id}/{token}", name="phone_show")
     */
    public function showAction($token, Token $tokenVerify, Phone $phone, SerializerInterface $serializer)
    {
        $tokenVerify->verify($token);

        $data = $serializer->serialize($phone, 'json', ['groups' => 'detail']);

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
