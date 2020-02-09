<?php

namespace App\Link;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ClientLink
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getLinks()
    {
        $links = [
            'create' =>$this->urlGenerator->generate('client_create',[], 0),
            'modify' =>$this->urlGenerator->generate('client_modify',['email'=>'email'], 0),
            'delete' =>$this->urlGenerator->generate('client_delete',['email'=>'email'], 0),
            'show' => $this->urlGenerator->generate('client_show',['email'=>'email'], 0),
            'show list' => $this->urlGenerator->generate('client_all',['page'=>'page'], 0),
            'create relation [POST]' => $this->urlGenerator->generate('relation_create',['email'=>'email', 'serialNumber'=>'serialNumber'], 0),
            'delete relation [DELETE]' => $this->urlGenerator->generate('relation_delete', ['email'=>'email', 'serialNumber'=>'serialNumber'], 0),
        ];

        return $links;
    }
}