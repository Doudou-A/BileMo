<?php

namespace App\Controller;

use App\Link\PhoneLink;
use App\Service\PhoneManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneCreateController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @Route("/phones", name="phone_create", methods={"POST"})
     */
    public function phoneCreate(PhoneManager $phoneManager, ValidatorInterface $validator, PhoneLink $phonelink)
    {
        $entity = $phoneManager->getData();
        
        $errors = $validator->validate($entity);

        if (count($errors)) {
            return new Response($errors, Response::HTTP_BAD_REQUEST);
        }

        $phone = $phoneManager->add($entity);

        $phone->setLinks($phonelink->getlinks());

        return $phoneManager->responseDetail($phone)
                ->setStatusCode(Response::HTTP_CREATED);
    }
}