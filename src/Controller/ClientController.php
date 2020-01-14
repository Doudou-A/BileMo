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

class ClientController extends AbstractController implements TokenAuthenticatedController
{
    
    /**
     * @ROUTE("/client", name="add_client", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="add a client",
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="firstName",
     *     in="query",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="Email",
     *     in="query",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="serialNumber",
     *     in="query",
     *     type="integer",
     *     description="serialNumber of phone you want to add to client. It's not a obligation, you can add a client without phone"
     * )
     */
    public function addClient(ClientManager $clientManager, PhoneManager $phoneManager)
    {
        $user = $this->getUser();

        $client = $clientManager->add($user);

        $phone = $phoneManager->getData();

        if ($phone->getSerialNumber() != null)
        {
            $phoneManager->relationAdd($client);
        }
        
        return $clientManager->responseList($client);
    }

    /**
     * @Route("/client", name="client_modify", methods={"PUT"})
     * * @SWG\Response(
     *     response=200,
     *     description="Modify the name and/or the firstName. You can't change the email but you must to post him.",
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="firstName",
     *     in="query",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="Email",
     *     in="query",
     *     type="string",
     *     description="Email of the client than you want to modify"
     * )
     */
    public function clientModify(ClientManager $clientManager, UserManager $userManager)
    {
        $user = $this->getUser();

        $client = $userManager->verify($user);

        if ($client == null)
        {
            new Response('Vous n\'êtes pas autorisé à faire cette action !', Response::HTTP_FORBIDDEN);
        }

        $client = $clientManager->modify($client);

        return $clientManager->responseDetail($client);
    }

    /**
     * @ROUTE("/client", name="delete-client", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Delete a client",
     * )
     * @SWG\Parameter(
     *     name="Email",
     *     in="query",
     *     type="string",
     *     description=""
     * )
     */
    public function deleteClient(ClientManager $clientManager, PhoneManager $phoneManager, UserManager $userManager, Message $message)
    {
        $user = $this->getUser();

        $client = $userManager->verify($user);

        if ($client == null)
        {
            new Response('Vous n\'êtes pas autorisé à faire cette action !', Response::HTTP_FORBIDDEN);
        }

        $phones = $phoneManager->findByClient($client);

        foreach ($phones as $phone) {
           $phoneManager->deleteClient($phone);
        }

        $clientManager->remove($client);
    
        return new Response(Response::HTTP_OK);
    }

    /**
     * @Route("/client/{page}", name="client_all", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show all of your clients",
     * )
     * @SWG\Parameter(
     *     name="page on URL",
     *     in="query",
     *     type="integer"
     * )
     */
    public function showClientAll(ClientManager $clientManager, $page)
    {
        $user = $this->getUser()->getId();

        $clients = $clientManager->pagination($page, $user);

        return $clientManager->responseList($clients);
    }

    /**
     * @Route("/client", name="client_show", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show a client",
     * )
     * @SWG\Parameter(
     *     name="Email",
     *     in="query",
     *     type="string",
     *     description="Email of the client than you want to show informations"
     * )
     */
    public function showClientAction(ClientManager $clientManager, UserManager $userManager)
    {
        $user = $this->getUser();

        $client = $userManager->verify($user);

        if ($client == null)
        {
            new Response('Vous n\'êtes pas autorisé à faire cette action !', Response::HTTP_FORBIDDEN);
        }

        return $clientManager->responseDetail($client);
    }
}
