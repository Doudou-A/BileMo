<?php

namespace App\Controller;

use App\Service\Message;
use App\Service\UserManager;
use App\Service\PhoneManager;
use App\Service\ClientManager;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @Route("/phone", name="phone_create", methods={"POST"})
     */
    public function phoneCreate(PhoneManager $phoneManager)
    {
        $phone = $phoneManager->add();
        
        return $phoneManager->responseDetail($phone);
    }

    /**
     * @Route("/phone", name="phone_delete", methods={"DELETE"})
     */
    public function phoneDelete(PhoneManager $phoneManager)
    {
        $phoneManager->delete();
        
        return new Response(Response::HTTP_OK);
    }

    /**
     * @Route("/phone", name="phone_modify", methods={"PUT"})
     */
    public function phoneModify(PhoneManager $phoneManager)
    {
        $phone = $phoneManager->modify();

        return $phoneManager->responseDetail($phone);
    }

    /**
     * @Route("/phone/client",  name="relation_create", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a relation beetween your client and a phone. You can create several ralation with one client",
     * )
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="serialNumber",
     *     in="query",
     *     type="integer"
     * )
     */
    public function relationCreate(PhoneManager $phoneManager, UserManager $userManager, ClientManager $clientManager)
    {

        $user = $this->getUser();

        $client = $userManager->verify($user);

        if ($client == null)
        {
            new Response('Vous n\'êtes pas autorisé à faire cette action !', Response::HTTP_FORBIDDEN);
        }

        $phoneManager->relationAdd($client);

        return $clientManager->responseList($client);
    }

    /**
     * @Route("/phone/client", name="relation_delete",  methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a relation beetween your client and a phone. You can create several ralation with one client",
     * )
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="serialNumber",
     *     in="query",
     *     type="integer"
     * )
     */
    public function relationDelete(PhoneManager $phoneManager, UserManager $userManager)
    {
        $user = $this->getUser();

        $verify = $userManager->verify($user);

        if ($verify == null)
        {
            new Response('Vous n\'êtes pas autorisé à faire cette action !', Response::HTTP_FORBIDDEN);
        }

        $phone = $phoneManager->relationDelete();

        return $phoneManager->responseDetail($phone);
    }

    /**
     * @Route("/phone/{page}", name="phone_get",  methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show all of phone available",
     * )
     * @SWG\Parameter(
     *     name="page on URL",
     *     in="query",
     *     type="integer"
     * )
     */
    public function showPhone(PhoneManager $phoneManager, $page)
    {
        $phones = $phoneManager->pagination($page);

        return $phoneManager->responseList($phones);
    }

    /**
     * @Route("/phone", name="phone_show", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show a phone",
     * )
     * @SWG\Parameter(
     *     name="serialNumber",
     *     in="query",
     *     type="string",
     *     description="serialNumber of the phone than you want to show informations"
     * )
     */
    public function showPhoneAction(PhoneManager $phoneManager)
    {

        $phone = $phoneManager->getPhone();

        return $phoneManager->responseDetail($phone);
    }
}
