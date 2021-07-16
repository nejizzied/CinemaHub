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
use App\Repository\FilmRepository;
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
     * @Route("/api/reservation/ajouter", name="reservation_ajouté" , methods={"post"})
     */
    public function cinemaEvaluation(Request $request, $id,
                                     UserRepository $userRepository,
                                     NormalizerInterface $Normalizer,
                                     CinemaRepository $cinemaRepository,
                                     AdminRepository $adminRepository,
                                     EvaluationRepository $evaluationRepository,
                                     ReservationRepository $reservationRepository,
                                     SalleDeProjectionRepository $salleDeProjectionRepository,
                                     EntityManagerInterface $em ,
    FilmRepository $filmRepository

    ): Response
    {
        $reservation = new Reservation();
        $data = json_decode($request->getContent(), true);
        empty($data['nbrTicket']) ? true : $reservation->setNbrTickets(($data['nbrTicket']));
        empty($data['idUser']) ? true : $reservation->setIdUser($userRepository->find($data['idUser']));
        $reservation->setIdFilm($filmRepository->find($id));
        $placesDispo = 0 ;
        $placesDispo = sizeof($reservationRepository->findBy(['idFilm' =>$id , 'status' => 'confirmé']));
        $salleProjecton = $salleDeProjectionRepository->findOneBy(['idFilm' => $id]);
        $placesDispo =$salleProjecton->getNbrPlaces() - sizeof($reservationRepository->findBy(['idFilm' =>$id , 'status' => 'confirmé']));

        $user = $userRepository->find($data['idUser']);
        if ($salleProjecton != null) {
            if ($reservation->getNbrTickets() <= $placesDispo) {
                $reservation->setStatus('en attente de confirmation');
                $user->setPointFidelite($user->getPointFidelite() + 2);
                $em->persist($user);
                $em->persist($reservation);
                $em ->flush();
                return new Response('Reservation Ajouté');
            }
            return new Response('aucune place disponible', Response::HTTP_BAD_REQUEST);
        }
        return new Response('pas de salle de projection trouvé', Response::HTTP_BAD_REQUEST);
    }


    /**
     * @Route("/api/reservation/annuler/{id}", name="reservation_annuler" )
     */
    public function annulerReservation(Request $request, $id,
                                     UserRepository $userRepository,
                                     NormalizerInterface $Normalizer,
                                     CinemaRepository $cinemaRepository,
                                     AdminRepository $adminRepository,
                                     EvaluationRepository $evaluationRepository,
                                     ReservationRepository $reservationRepository,
                                     SalleDeProjectionRepository $salleDeProjectionRepository,
                                     EntityManagerInterface $em
    ): Response
    {

        $reservation = $reservationRepository->find($id);

        if ($reservation != null) {

            $reservation->setStatus("annulé");
            $em=$this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em ->flush();

            $jsonContent = $Normalizer->normalize($reservation, 'json' , ['groups' => ['other' , 'read'] , 'enable_max_depth' => true]);
            $retour=json_encode($jsonContent);
            return new Response($retour);
        }
        return new Response('no reservation found', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/api/reservation/confirmer/{id}", name="reservation_confirmer" )
     */
    public function confirmerReservation(Request $request, $id,
                                     UserRepository $userRepository,
                                     NormalizerInterface $Normalizer,
                                     CinemaRepository $cinemaRepository,
                                     AdminRepository $adminRepository,
                                     EvaluationRepository $evaluationRepository,
                                     ReservationRepository $reservationRepository,
                                     SalleDeProjectionRepository $salleDeProjectionRepository,
                                     EntityManagerInterface $em
    ): Response
    {

        $reservation = $reservationRepository->find($id);

        if ($reservation != null) {

            $reservation->setStatus("confirmé");
            $em->persist($reservation);
            $em ->flush();

            $jsonContent = $Normalizer->normalize($reservation, 'json' , ['groups' => ['other' , 'read'] , 'enable_max_depth' => true]);
            $retour=json_encode($jsonContent);
            return new Response($retour);
        }
        return new Response('no reservation found', Response::HTTP_BAD_REQUEST);
    }

}
