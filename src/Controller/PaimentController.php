<?php


namespace App\Controller;


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

class PaimentController extends AbstractController
{

    /**
     * @Route("/api/chargerCompte/id", name="charger_user")
     */
    public function chargerCompte(Request $request , $id ,
                                  UserRepository $userRepository ,
                                  NormalizerInterface $Normalizer,
                                  CinemaRepository $cinemaRepository,
                                  AdminRepository$adminRepository ,
                                  EntityManagerInterface $em ,
    ): Response
    {
        //clé de l'api de paiment
        $clientSecret = "sk_test_51JFh3UATJA8D1NKoPu7E0H8FSf0VkcNyiuv975RimjZooQLWam26RZQM5QsAVggUKFL7osH9PxJISGNHudk86LTf00BuwjWg2C" ;
        $user = $userRepository->find($id);
        $data = json_decode($request->getContent(), true);
        empty($data['amount']) ? true : $user->setAmount(($data['amount']));

        if($user != null) {
            $em->persist($user);
            $em ->flush();
            $jsonContent = $Normalizer->normalize(['msg' => 'Compte chargé' , 'client_secret' => $clientSecret], 'json',['groups' => 'read' , 'enable_max_depth' => true]);
            $retour=json_encode($jsonContent);
            return new Response($retour);
        }

        return new Response("Account Not Found"  , Response::HTTP_BAD_REQUEST);

    }
}