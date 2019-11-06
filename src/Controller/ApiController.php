<?php

namespace App\Controller;


use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index(SerializerInterface $serializer)
    {
        $phone = new Phone();
        $phone
            ->setName("Iphone 11")
            ->setPrice(809)
            ->setColor("noir")
            ->setDescription('Le tout nouveau avec 2 appareils photos');

        $data = $serializer->serialize($phone, 'json');

        return new Response($data, 200, ['Content-Type'=>'application/json']);
    }
}
