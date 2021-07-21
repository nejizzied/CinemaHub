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
     * @Route("/api/reservations/ajouter", name="reservation_ajouté" , methods={"post"})
     */
    public function cinemaEvaluation(Request $request,
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

        empty($data['nbrTickets']) ? true : $reservation->setNbrTickets(($data['nbrTickets']));
        empty($data['idUser']) ? true : $reservation->setIdUser($userRepository->find($data['idUser']));
        empty($data['idFilm']) ? true : $id = $data['idFilm'];

        $reservation->setIdFilm($filmRepository->find($id));
        $placesDispo = 0 ;
        $placesDispo = sizeof($reservationRepository->findBy(['idFilm' =>$id , 'status' => 'confirmé']));
        $salleProjecton = $salleDeProjectionRepository->findOneBy(['idFilm' => $id]);
        //calul de nombre de places disponible
        $placesDispo =$salleProjecton->getNbrPlaces() - sizeof($reservationRepository->findBy(['idFilm' =>$id , 'status' => 'confirmé']));
        $user = $userRepository->find($data['idUser']);

        if ($salleProjecton != null)
        {
            if ($reservation->getNbrTickets() <= $placesDispo)
            {
                if($user->getAmount() >= ( $reservation->getNbrTickets() * $reservation->getIdFilm()->getPrix() ) )
                    {
                    $reservation->setStatus('en attente de confirmation');
                    $em->persist($reservation);
                    $em ->flush();
                    $jsonContent = $Normalizer->normalize(['msg' => 'Reservation Ajouté'], 'json' , ['groups' => ['other' , 'read'] , 'enable_max_depth' => true]);
                    $retour=json_encode($jsonContent);
                    return new Response($retour);
                 }else
                 {
                    $jsonContent = $Normalizer->normalize(['msg' => 'Solde Insuffisant'], 'json' , ['groups' => ['other' , 'read'] , 'enable_max_depth' => true]);
                    $retour=json_encode($jsonContent);
                    return new Response($retour);
                }

            }
            $jsonContent = $Normalizer->normalize(['msg' => 'aucune place disponible' ], 'json' , ['groups' => ['other' , 'read'] , 'enable_max_depth' => true]);
            $retour=json_encode($jsonContent);
            return new Response($retour);
        }

        $jsonContent = $Normalizer->normalize(
            ['msg' => 'pas de salle de projection trouvé' ], 'json' , ['groups' => ['other' , 'read'] , 'enable_max_depth' => true]);
        $retour=json_encode($jsonContent);
        return new Response($retour, Response::HTTP_BAD_REQUEST);
    }


    /**
     * @Route("/api/reservations/annuler/{id}", name="reservation_annuler" )
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
     * @Route("/api/reservations/confirmer/{id}", name="reservation_confirmer" )
     */
    public function confirmerReservation(Request $request,
                                         $id,
                                     UserRepository $userRepository,
                                     NormalizerInterface $Normalizer,
                                     CinemaRepository $cinemaRepository,
                                     AdminRepository $adminRepository,
                                     EvaluationRepository $evaluationRepository,
                                     ReservationRepository $reservationRepository,
                                     SalleDeProjectionRepository $salleDeProjectionRepository,
                                     EntityManagerInterface $entityManager
    ): Response
    {
        $reservation = $reservationRepository->find($id);
        $user = $reservation->getIdUser();

        if ($reservation != null) {

            $reservation->setStatus("confirmé");
            $user->setPointFidelite($user->getPointFidelite() + 2);
            $user->setAmount($user->getAmount()- $reservation->getNbrTickets() * $reservation->getIdFilm()->getPrix());

            $entityManager->persist($user);
            $entityManager->persist($reservation);
            $entityManager ->flush();

            $jsonContent = $Normalizer->normalize($reservation, 'json' , ['groups' => ['other' , 'read'] , 'enable_max_depth' => true]);
            $retour=json_encode($jsonContent);
            return new Response($retour);
        }
        return new Response('no reservation found', Response::HTTP_BAD_REQUEST);
    }
}
