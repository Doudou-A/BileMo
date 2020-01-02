<?php

namespace App\Controller;

use App\Service\Message;
use App\Service\UserManager;
use App\Service\PhoneManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Swagger\Annotations as SWG;

class PhoneController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @Route("/admin/phone", name="phone_create", methods={"POST"})
     */
    public function phoneCreate(PhoneManager $phoneManager)
    {
        $phone = $phoneManager->add();
        
        return $phoneManager->responseDetail($phone);
    }

    /**
     * @Route("/admin/phone", name="phone_delete", methods={"DELETE"})
     */
    public function phoneDelete(PhoneManager $phoneManager, Message $message)
    {
        $phoneManager->delete();
        
        return $message->removeSuccess();
    }

    /**
     * @Route("/admin/phone", name="phone_modify", methods={"PUT"})
     */
    public function phoneModify(PhoneManager $phoneManager)
    {
        $phone = $phoneManager->modify();

        return $phoneManager->responseDetail($phone);
    }

    /**
     * @Route("/relation",  name="relation_create", methods={"POST"})
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
    public function relationCreate(PhoneManager $phoneManager, UserManager $userManager, Message $message)
    {

        $user = $this->getUser();

        $client = $userManager->verify($user);

        $phoneManager->relationAdd($client);

        return $message->modifySuccess();
    }

    /**
     * @Route("/relation", name="relation_delete",  methods={"DELETE"})
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

        $userManager->verify($user);

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
