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
        $phone->setDateCreated(new \DateTime());

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($phone);
        $manager->flush();

        return new Response('Ajout du téléphone effectué avec succès !', Response::HTTP_CREATED);
    }

    /**
     * @Route("/phones/delete/{id}", name="phone_delete")
     */
    public function Delete(Phone $phone)
    {        
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($phone);
        $manager->flush();

        return new Response('Suppression effectuée avec succès !', Response::HTTP_CREATED);
    }

    /**
     * @Route("/phones/modify/{id}", name="phone_modify")
     */
    public function Modify(Phone $phone, Request $request, SerializerInterface $serializer)
    {
        $data = $request->getContent();
        $phoneData = $serializer->deserialize($data, Phone::class, 'json');

        $phone->setName($phoneData->getName());
        $phone->setContent($phoneData->getContent());

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($phone);
        $manager->flush();

        return new Response('Modifications effectuées avec succès !', Response::HTTP_CREATED);
    }

    /**
     * @Route("/phones/all/{page}", name="phone_all")
     */
    public function showAll(SerializerInterface $serializer, PhoneRepository $repo, $page)
    {
        $nbPhonesParPage = 5;

        $phones = $repo->findAll(); 

        $phones = $repo->findAllPagineEtTrie($page, $nbPhonesParPage);

        $data = $serializer->serialize($phones, 'json', ['groups' => 'list']);

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;

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
    }
}
