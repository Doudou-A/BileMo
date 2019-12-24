<?php

namespace App\Service;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AddEntity
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function setData($entity)
    {
        $hash = $this->encoder->encodePassword($entity, $entity->getPassword());

        $entity->setDateCreated(new \DateTime());
        $entity->setPassword($hash);

        return $entity;
    }
}