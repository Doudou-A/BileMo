<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController
{
    /**
     * @Route("/phones", name="phone_create")
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {        
        $phone = new Phone;
        $errors = $validator->validate($phone);

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;
    
            return new Response($errorsString);
        }

        $data = $request->getContent();
        $phone = $serializer->deserialize($data, Phone::class, 'json');

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($phone);
        $manager->flush();

        return new Response('', Response::HTTP_CREATED);
    }

    /**
     * @Route("/phones/all", name="phone_all")
     */
    public function showAll(SerializerInterface $serializer, PhoneRepository $repo)
    {
        $phones = $repo->findAll();

        $data = $serializer->serialize($phones, 'json', ['groups' => 'list']);

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

        return $this->render('phone/index.html.twig', [
            'controller_name' => 'PhoneController',
        ]);
    }

    /**
     * @Route("/phones/{id}", name="phone_show")
     */
    public function showAction(Phone $phone, SerializerInterface $serializer)
    {
        $data = $serializer->serialize($phone, 'json', ['groups' => 'detail']);

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

        return $this->render('phone/index.html.twig', [
            'controller_name' => 'PhoneController',
        ]);
    }
}
