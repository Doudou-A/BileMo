<?php

namespace App\Link;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PhoneLink
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;

    }

    public function getLinks()
    {
        $links = [
            'show' => $this->urlGenerator->generate('phone_show',['serialNumber'=>'serialNumber'], 0),
            'show list' => $this->urlGenerator->generate('phone_list',['page'=>'page'], 0),
        ];

        return $links;
    }
}