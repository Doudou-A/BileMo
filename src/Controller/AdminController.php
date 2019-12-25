<?php

namespace App\Controller;

use App\Entity\Admin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    
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
        $admin->setPassword($hash);


        $manager = $this->getDoctrine()->getManager();
        $manager->persist($admin);
        $manager->flush();

        return new Response('Ajout effectuées avec succès !', Response::HTTP_CREATED);
    }


}
