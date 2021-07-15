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
     * @Route("/api/reservation/{id}", name="reservation_test" , methods={"post"})
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
        empty($data['nbTicket']) ? true : $cinema->setNomCinema($data['nbTicket']);
        $nbrTicket = $request->get('nbTicket');


        $reservation->setNbrTickets($nbrTicket);
        $salleProjecton = new SalleDeProjection();
        $salleProjecton = $salleDeProjectionRepository->findBy(['idFilm'=>$id]);

        if($reservation->getNbrTickets() <= $salleProjecton->getNbrPlaces())
        {
            return new Response('done');
        }
        return new Response('Cant add' , Response::HTTP_BAD_REQUEST);

    }
}
