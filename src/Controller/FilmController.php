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


class FilmController extends AbstractController
{
    /**
     * @Route("/api/films/cinema/{id}", name="getListFilmsByCinema")
     */
    public function getListFilms( $id ,
        UserRepository $userRepository ,
        NormalizerInterface $Normalizer,
        CinemaRepository $cinemaRepository,
        AdminRepository $adminRepository,
        EvaluationRepository $evaluationRepository,
        FilmRepository $filmRepository ,
        SalleDeProjectionRepository $salleDeProjectionRepository,
    ReservationRepository $reservationRepository
    ): Response
    {
        $films = [];
        $salles = $salleDeProjectionRepository->findBy(['idCinema' => $id ]);

        foreach ($salles as $s )
        {

            $placesDispo =$s->getNbrPlaces() - sizeof($reservationRepository->findBy(['idFilm' =>$s->getIdFilm()->getId() , 'status' => 'confirmé']));

            if( $placesDispo )
            {
                $c = $s->getIdFilm();
                $c->setStatus("complet");
            }
            array_push($films , $s->getIdFilm());
        }

        $jsonContent = $Normalizer->normalize($films, 'json' , ['groups' => ['other' , 'read'] , 'enable_max_depth' => true]);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }
}