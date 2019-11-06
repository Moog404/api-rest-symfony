<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/api")
 */
class PhoneController extends AbstractController
{
    /**
     * @Route("/phones/{id}", name="phone.show", methods={"GET"})
     */
    public function show(Phone $phone, PhoneRepository $phoneRepository, SerializerInterface $serializer)
    {
        $phone = $phoneRepository->find($phone->getId());
        $data = $serializer->serialize($phone, 'json', [
            "groups"=>["show"]
        ]);
        return new Response($data, 200, [
            'Content-type'=>'application/json'
        ]);

    }

    /**
     * @Route("/phones/{page<\d+>?1}", name="phone.list", methods={"GET"})
     */
    public function index(Request $request, PhoneRepository $phoneRepository, SerializerInterface $serializer)
    {
        $page = $request->query->get('page');
        if (is_null($page)||$page<1) {
            $page = 1;
        }
        $limit=10;
        $phones = $phoneRepository->findAllPhones($page,$limit);
        $data = $serializer->serialize($phones, 'json', [
            "groups"=>["list"]
        ]);
        return new Response($data, 200, [
            'Content-type'=>'application/json'
        ]);
    }

    /**
     * @Route("/phones", name="phone.add", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $phone=$serializer->deserialize($request->getContent(), Phone::class, "json");
        $entityManager->persist($phone);
        $entityManager->flush();
        $data = [
            'status'=>201,
            'message'=>'le téléphone a bien été ajouté'
        ];
        return new JsonResponse($data, 201);
    }

}