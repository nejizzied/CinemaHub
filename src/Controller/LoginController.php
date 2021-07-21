<?php

namespace App\Controller;
use App\Form\PublType;
use App\Form\UpdateType;
use App\Entity\Publicite;


use App\Repository\AdminRepository;
use App\Repository\CinemaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PubliciteRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class LoginController extends AbstractController
{
    /**
     * @Route("/api/login/{email}/{password}", name="login_user")
     */
    public function login_User(Request $request , string $password , string $email ,
                               UserRepository $userRepository ,
                                NormalizerInterface $Normalizer,
                                CinemaRepository $cinemaRepository,
                                AdminRepository$adminRepository): Response
    {
        $user = $userRepository->findBy(['email'=> $email , 'password' => $password]) ;

        if($user != null) {
            $user["role"] = "user";
            $jsonContent = $Normalizer->normalize($user, 'json',['groups' => 'read' , 'enable_max_depth' => true]);
            $retour=json_encode($jsonContent);
            return new Response($retour);
        }

            $user = $cinemaRepository->findBy(['email'=> $email , 'password' => $password]);
        if($user != null) {
            $user["role"] = "cinema" ;
            $jsonContent = $Normalizer->normalize($user, 'json',['groups' => 'read' , 'enable_max_depth' => true]);
            $retour=json_encode($jsonContent);
            return new Response($retour);
        }

            $user = $adminRepository->findBy(['email'=> $email , 'password' => $password]);
        if($user != null) {
            $user["role"] = "admin" ;
            $jsonContent = $Normalizer->normalize($user, 'json',['groups' => 'read' , 'enable_max_depth' => true]);
            $retour=json_encode($jsonContent);
            return new Response($retour);
        }

            return new Response("Account Not Found"  , Response::HTTP_BAD_REQUEST);

    }



}