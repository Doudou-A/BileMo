<?php

namespace App\Controller;

use App\Service\Message;
use App\Service\UserManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Swagger\Annotations as SWG;

class UserController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @ROUTE("/admin/user", name="add_user", methods={"POST"})
     */
    public function addUser(UserManager $userManager)
    {
        $user = $userManager->add();

        return $userManager->response($user);
    }

    /**
     * @ROUTE("/user", name="modify_user", methods={"PUT"})
     * @SWG\Response(
     *     response=200,
     *     description="Modify your information about your account. You can change your name and/or firstName and/or password",
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
     *     name="password",
     *     in="query",
     *     type="string"
     * )
     */
    public function modifyUser(UserManager $userManager, Message $message)
    {
        $userCo = $this->getUser();

        $userManager->verifyUser($userCo);

        $user = $userManager->modify();

        return $userManager->response($user);
    }

    /**
     * @ROUTE("/admin/user", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser(UserManager $userManager, Message $message)
    {
        $user = $userManager->getUser();

        $userManager->delete($user);

        return $message->removeSuccess();
    }
}
