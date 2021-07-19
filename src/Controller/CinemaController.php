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
use Symfony\Contracts\HttpClient\HttpClientInterface;


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

    /**
     * @Route("/api/cinema/map/", name="getMapListCinema")
     */
    public function getMapListCinema(
        UserRepository $userRepository ,
        NormalizerInterface $Normalizer,
        CinemaRepository $cinemaRepository,
        AdminRepository$adminRepository,
        EvaluationRepository $evaluationRepository,
        HttpClientInterface $client
    ): Response
    {

        $cinemas = $cinemaRepository->findAll();
        $res = [];
        foreach ($cinemas as &$cin)
        {

            $apiUrl = "https://autocomplete.search.hereapi.com/v1/geocode?q=".$cin->getAdresse()."&apiKey=9MrJVMUANOrj3rt_rrx6ED2Tsdqc7UIicY2zSFwLeIw";

            $response = $client->request(
                'GET',
                $apiUrl
            );

            $statusCode = $response->getStatusCode();

            $content = $response->getContent();
            // $content = '{"id":521583, "name":"symfony-docs", ...}'
            //$content = $response->toArray();
            // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

            $content = json_decode($content, true);
            $item = $content["items"][0];

            $cin->setLan( $item["position"]["lng"]) ;
            $cin->setLat($item["position"]["lat"]);
            array_push($res , $cin) ;
        }

        $jsonContent = $Normalizer->normalize($res, 'json' , ['groups' => ['map'] , 'enable_max_depth' => true]);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }
}