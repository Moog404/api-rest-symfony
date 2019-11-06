<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, Request $request,ValidatorInterface $validator)
    {
        $values=json_decode($request->getContent());
        if(isset($values->username, $values->password)){ // On vérifie si les valeurs existent
            $user = new User();
            $user->setUsername($values->username)
                ->setPassword($passwordEncoder->encodePassword($user, $values->password))
                ->setRoles($user->getRoles());

            $errors=$validator->validate($user); // si erreur ->envoyé en objet
            if(count($errors)) {
                $errors=$serializer->serialize($errors, "json"); // on le transforme pour le retourner en json
                return new Response($errors, 500, [
                    'Content-Type'=>'application/json'
                ]);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            $data=[
                'status'=>201,
                'message'=>'L\'utilisateur a bien été créé'
            ];

            return new JsonResponse($data, 201);
        }

        $data=[
            'status'=>500,
            'message'=>"Vous devez renseigner les champs Username et Password"
        ];
        return new JsonResponse($data, 500);
    }

    /**
     * @Route("/login",name="login",methods={"POST"})
     */
    public function login(Request $request)
    {
        $user=$this->getUser();
        return $this->json([
            'username'=>$user->getUsername(),
            'roles'=>$user->getRoles()
        ]);
    }
}
