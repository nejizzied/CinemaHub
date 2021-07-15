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


class ReservationController extends AbstractController
{
    /**
     * @Route("/api/reservation/test/{id}", name="reservation_test" , methods={"post"})
     */
    public function cinemaEvaluation(Request $request , $id ,
                                     UserRepository $userRepository ,
                                     NormalizerInterface $Normalizer,
                                     CinemaRepository $cinemaRepository,
                                     AdminRepository$adminRepository,
                                     EvaluationRepository $evaluationRepository ,
                                    ReservationRepository $reservationRepository ,
                                    SalleDeProjectionRepository $salleDeProjectionRepository,
    ): Response
    {

        $reservation = new Reservation();
        $data = json_decode($request->getContent(), true);
        empty($data['nbrTicket']) ? true : $reservation->setNbrTickets(($data['nbrTicket']));


        $salleProjecton = new SalleDeProjection();
        $salleProjecton = $salleDeProjectionRepository->findOneBy(['idFilm'=>$id]);
        if($salleProjecton != null) {
            if ($reservation->getNbrTickets() <= $salleProjecton->getNbrPlaces()) {
                return new Response('done');
            }
            return new Response('no places availble', Response::HTTP_BAD_REQUEST);
        }
        return new Response('no projection found', Response::HTTP_BAD_REQUEST);
    }
}
