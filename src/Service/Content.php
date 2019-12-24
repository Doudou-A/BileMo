<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Phone;
use App\Entity\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class Content
{
    private $request;
    private $serializer;

    public function __construct(Request $request, SerializerInterface $serializer)
    {
        $this->request = $request;
        $this->serializer = $serializer;
    }
    public function getData($entity)
    {
        $data = $this->request->getContent();

        switch ($entity) {
            case 'user':
                $class = User::class;
                break;
            case 'phone':
                $class = Phone::class;
                break;
            case 'client':
                $class = Client::class;
                break;
        }
        
        $dataDeserialize = $this->serializer->deserialize($data, $class, 'json');

        return $dataDeserialize;
    }
}