<?php

namespace App\Controller;
use App\Entity\Reservation;
use App\Entity\SalleDeProjection;
use App\Form\PublType;
use App\Form\UpdateType;
use App\Entity\Publicite;


use App\Repository\AdminRepository;
use App\Repository\CinemaRepository;
use App\Repository\EvaluationRepository;
use App\Repository\ReservationRepository;
use App\Repository\SalleDeProjectionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PubliciteRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class StatistiqueController extends AbstractController
{
    /**
     * @Route("/api/statistiques/reservations", name="statistiques_reservation" , methods={"get"})
     */
    public function gestStats(Request $request  ,
                                     UserRepository $userRepository ,
                                     NormalizerInterface $Normalizer,
                                     CinemaRepository $cinemaRepository,
                                     AdminRepository$adminRepository,
                                     EvaluationRepository $evaluationRepository ,
                                     ReservationRepository $reservationRepository ,
                                     SalleDeProjectionRepository $salleDeProjectionRepository,
    ): Response
    {
        $nbrResConfirmé = sizeof($reservationRepository->findBy(['status' => 'confirmé']));
        $nbrResAnnulé = sizeof($reservationRepository->findBy(['status' => 'annulé']));
        $nbrResEnAttente = sizeof($reservationRepository->findBy(['status' => 'en attente de confirmation']));

         $jsonContent = $Normalizer->normalize(['confirmé' => $nbrResConfirmé , 'annulé' => $nbrResAnnulé , 'en attente' => $nbrResEnAttente], 'json',['groups' => 'read' , 'enable_max_depth' => true]);
         $retour=json_encode($jsonContent);
        return new Response($retour);
    }
}
