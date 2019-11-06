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
use Symfony\Component\Validator\Validator\ValidatorInterface;


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
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $phone=$serializer->deserialize($request->getContent(), Phone::class, "json");
        $errors=$validator->validate($phone);
        if(count($errors)) { //si une erreur existe
            $error = $serializer->serialize($errors, 'json');
            return new Response($error, 500, [
                'Content-Type'=>"application/json"
            ]);
        }

        $entityManager->persist($phone);
        $entityManager->flush();
        $data = [
            'status'=>201,
            'message'=>'le téléphone a bien été ajouté'
        ];
        return new JsonResponse($data, 201);
    }

    /**
     * @Route("/phones/{id}", name="phone.edit", methods={"PUT"})
     */
    public function edit(Phone $phone,Request $request,SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $phoneEdit=$entityManager->getRepository(Phone::class)->find($phone->getId()); //récupère les données de l'id
        $dataRequest=json_decode($request->getContent());  // pour correspondre au format de l'objet envoyé par doctrine
        foreach($dataRequest as $key=>$value){
            if($key && !empty($value)){ // si la clé existe et la valeur n'est pas vide
                $name=ucfirst($key);
                $setter='set'.$name; //on attend à avoir setName, setPrice...
                $phoneEdit->$setter($value); //on modifie la valeur correspondant à la clé
            }
        }
        $errors=$validator->validate($phoneEdit);
        if(count($errors)) { //si une erreur existe
            $error = $serializer->serialize($errors, 'json');
            return new Response($error, 500, [
                'Content-Type'=>"application/json"
            ]);
        }
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'Le téléphone a bien été mis à jour'
        ];
        return new JsonResponse($data);
    }

    /**
     * @Route("/phones/{id}", name="phone.delete", methods={"DELETE"})
     */
    public function delete(Phone $phone, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($phone);
        $entityManager->flush();
        $data = [
            'status'=>204,
            'message'=>'le téléphone a bien été supprimé'
        ];
        return new JsonResponse($data);
    }

}
