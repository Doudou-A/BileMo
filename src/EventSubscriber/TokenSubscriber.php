<?php

namespace App\EventSubscriber;

use Firebase\JWT\JWT;
use App\Repository\UserRepository;
use App\Repository\AdminRepository;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Controller\TokenAuthenticatedController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TokenSubscriber implements EventSubscriberInterface
{
    private $repoAdmin;
    private $repoUser;

    public function __construct(AdminRepository $repoAdmin, UserRepository $repoUser)
    {
        $this->repoAdmin = $repoAdmin;
        $this->repoUser = $repoUser;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof TokenAuthenticatedController) {
            $request = $event->getRequest()->headers->get('Authorization');

            $key = "token";
            $decoded = JWT::decode($request, $key, array('HS256'));

            $token_array = (array) $decoded;
            
            $id = $token_array['Id'];

            $entity = $this->repoAdmin->find($id);
            if ($entity == null) {
                $entity = $this->repoUser->find($id);
            }

            $token = array(
                'Id' => $entity->getId(),
                'FirstName' => $entity->getFirstName(),
                'Name' => $entity->getName(),
                'Username' => $entity->getUsername()
            );

            if ($token != $token_array) {
                throw new AccessDeniedHttpException('Cette action nécessite un token valide !');
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
