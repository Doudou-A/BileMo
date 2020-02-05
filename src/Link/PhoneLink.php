<?php

namespace App\Link;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;

class PhoneLink
{
    private $urlGenerator;
    private $serializer;

    public function __construct(UrlGeneratorInterface $urlGenerator, SerializerInterface $serializer)
    {
        $this->urlGenerator = $urlGenerator;
        $this->serializer = $serializer;
    }

    public function getLinks()
    {
        $links = [
            'create [POST]' => $this->urlGenerator->generate('relation_create',[], 0),
            'delete [DELETE]' => $this->urlGenerator->generate('relation_delete', [], 0),
            'show' => $this->urlGenerator->generate('phone_show',[], 0),
        ];

        return $links;
    }
}