<?php

namespace App\Controller;
use App\Entity\Commentaire;
use App\Entity\Reservation;
use App\Entity\SalleDeProjection;
use App\Form\PublType;
use App\Form\UpdateType;
use App\Entity\Publicite;


use App\Repository\AdminRepository;
use App\Repository\CinemaRepository;
use App\Repository\CommentaireRepository;
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


class CommentaireController extends AbstractController
{
    /**
     * @Route("/api/commentaires/ajouter", name="commentaire_ajouter" , methods={"post"})
     */
    public function commentaireAjouter(Request $request,
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
        $commentaire = new Commentaire();
        $data = json_decode($request->getContent(), true);
        empty($data['text']) ? true : $commentaire->setText(($data['text']));
        empty($data['idUser']) ? true : $commentaire->setIdUser($userRepository->find($data['idUser']));
        empty($data['idFilm']) ? true : $commentaire->setIdFilm($filmRepository->find($data['idFilm']));

                $em->persist($commentaire);
                $em ->flush();

                $jsonContent = $Normalizer->normalize(['msg' => 'Commentaire Ajouté'], 'json' , ['groups' => ['other' , 'read'] , 'enable_max_depth' => true]);
                $retour=json_encode($jsonContent);
                return new Response($retour);
            }

    /**
     * @Route("/api/commentaires/supprimer/{id}", name="commentaire_supprimer" , methods={"delete"})
     */
    public function commentaireSupprimer(Request $request,
                                       $id ,
                                       UserRepository $userRepository,
                                       NormalizerInterface $Normalizer,
                                       CinemaRepository $cinemaRepository,
                                       AdminRepository $adminRepository,
                                       EvaluationRepository $evaluationRepository,
                                       ReservationRepository $reservationRepository,
                                       SalleDeProjectionRepository $salleDeProjectionRepository,
                                       EntityManagerInterface $em ,
                                       FilmRepository $filmRepository ,
                                        CommentaireRepository $commentaireRepository

    ): Response
    {
        $commentaire = new Commentaire();
        $commentaire = $commentaireRepository->find($id);

        $em->remove($commentaire);
        $em ->flush();

        $jsonContent = $Normalizer->normalize(['msg' => 'Commentaire supprimer'], 'json' , ['groups' => ['other' , 'read'] , 'enable_max_depth' => true]);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }



}
