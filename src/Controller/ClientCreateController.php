<?php

namespace App\Controller;

use App\Link\ClientLink;
use App\Service\PhoneManager;
use App\Service\ClientManager;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientCreateController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @ROUTE("/client", name="client_create", methods={"POST"})
     * @SWG\Response(
     *     response=201,
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
    public function clientCreate(ClientManager $clientManager, ClientLink $clientlink, ValidatorInterface $validator)
    {
        $user = $this->getUser();

        $entity = $clientManager->getData();
        
        $errors = $validator->validate($entity);

        if (count($errors)) {
            return new Response($errors, Response::HTTP_BAD_REQUEST);
        }

        $client = $clientManager->add($user, $entity);

        $client->setLinks($clientlink->getlinks());

        return $clientManager->responseDetail($client)
                ->setStatusCode(Response::HTTP_CREATED);
    }
}
