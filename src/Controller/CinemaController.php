<?php

namespace App\Controller;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Form\PublType;
use App\Form\UpdateType;
use App\Entity\Publicite;


use App\Repository\AdminRepository;
use App\Repository\CinemaRepository;
use App\Repository\EvaluationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PubliciteRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class CinemaController extends AbstractController
{
    /**
     * @Route("/api/cinema/list/", name="getListCinema")
     */
    public function getListCinema(
                                     UserRepository $userRepository ,
                                     NormalizerInterface $Normalizer,
                                     CinemaRepository $cinemaRepository,
                                     AdminRepository$adminRepository,
                                     EvaluationRepository $evaluationRepository
    ): Response
    {
        $cinemas = $cinemaRepository->findAll();
        $res = [];
        foreach ($cinemas as &$cin)
        {
            $rating = 0 ;
            $evaluations = $evaluationRepository->findBy(['idCinema' => $cin]);
            $evaluationNumber = sizeof($evaluations);
            foreach ($evaluations as $ev) {
                $rating += $ev->getNote();
            }
            if($evaluationNumber != 0)
            $rating = $rating / $evaluationNumber ;
            $cin->setRating( $rating) ;
            array_push($res , $cin) ;
        }



        $jsonContent = $Normalizer->normalize($res, 'json' , ['groups' => ['other' , 'read'] , 'enable_max_depth' => true]);
        $retour=json_encode($jsonContent);
        return new Response($retour);

    }
}
