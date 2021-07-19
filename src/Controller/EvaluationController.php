<?php

namespace App\Controller;
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


class EvaluationController extends AbstractController
{
    /**
     * @Route("/api/evaluation/cinema/{id}", name="evaluation_cinema")
     */
    public function cinemaEvaluation(Request $request , $id ,
                               UserRepository $userRepository ,
                               NormalizerInterface $Normalizer,
                               CinemaRepository $cinemaRepository,
                               AdminRepository$adminRepository,
                                EvaluationRepository $evaluationRepository
    ): Response
    {
            $evaluations = $evaluationRepository->findBy(['idCinema' => $cinemaRepository->find($id)]);
            $rating = 0 ;
            if($evaluations != null) {
                foreach ($evaluations as $ev) {
                    $rating += $ev->getNote();
                }
                if (sizeof($evaluations)!=0)
                $rating = $rating / sizeof($evaluations);
            }
            $jsonContent = $Normalizer->normalize(['rating' => $rating], 'json',['groups' => 'read' , 'enable_max_depth' => true]);
            $retour=json_encode($jsonContent);
            return new Response($retour);

    }
}
