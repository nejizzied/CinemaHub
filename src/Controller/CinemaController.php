<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cinema;
use App\Repository\CinemaRepository;
use App\Manager\CinemaManager;
use App\Services\GoogleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/cinema/v1", name="cinemas")
 */
class CinemaController extends AbstractApiController
{
    private $cinemaRepo ;

    private $cinemaManager ;

    private $MapApiService ;

  
    /**
     * Constructeur For Injection Dependency
     */

    public function __construct(CinemaRepository $cinemaRepository , CinemaManager $cinemaManager , GoogleService $GoogleService)
    {
         $this->cinemaRepo =  $cinemaRepository ;
         $this->cinemaManager = $cinemaManager ;
         $this->MapApiService = $GoogleService ;
    }

    /**
     * @Route("/", name="index_cinema", methods={"GET"})
     */
    public function ListCinemaAction(CinemaRepository $cinemaRepository)
    {

        if (!$this->cinemaRepo->findAll()) {
            return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'List Empty '], Response::HTTP_INTERNAL_SERVER_ERROR);
         
        }
       
       return $this->respond(['status' => Response::HTTP_OK , 'message' => ' Availaible Cinema ' ,'data' => $this->cinemaRepo->findAll() ] , 200);

    }


    /**
     * @Route("/cinema/{id}", name="show_cinema", methods={"GET"})
     */
    public function showAction(Request $request): Response
    {
        $cinema = $this->getDoctrine()->getRepository(Cinema::class)->find($request->get('id'));

        if (!$cinema) {
            return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
         
        }

        return $this->respond(['status' => Response::HTTP_OK , 'message' => 'details for each cinema' ,'data' => $cinema ] , 200);

    }


    /**
     * @Route("/new", name="create_cinema", methods={"POST", "GET"})
     */
    public function createAction(Request $request , SerializerInterface $serializer): Response
    {

        $cinema = $serializer->deserialize($request->getContent(), Cinema::class, 'json');
      dd($cinema->getAdresse()) ;
         $latlong=  $this->MapApiService->GetLatLong($cinema->getAdresse());

           dd($latlong);
        // $latitude = $latlong->results[0]->geometry->location->lat;
        // $longitude = $latlong->results[0]->geometry->location->lng;
        // $cinema->setLatitude($latitude);
        // $cinema->setLongitude($longitude);

      
           
        $cinema = $this->cinemaManager->persistCinemaDB($cinema);
        
         return $this->respond(['status' => Response::HTTP_OK , 'message' => 'Successfully Added Cinema ' ,'data' => $cinema ] , 200);

    }


       /**
     * @Route("/edit/{id}", name="edit_cinema", methods={"PUT", "GET"})
     */
    public function editAction(Request $request , SerializerInterface $serializer , $id): Response
    {

        $cinema = $this->cinemaRepo->findOneBy(['id' => $id]);
        if (empty($cinema)) {
            return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
         
        }

        $data = json_decode($request->getContent(), true);

        empty($data['nom_cinema']) ? true : $cinema->setNomCinema($data['nom_cinema']);
        empty($data['num_tel']) ? true : $cinema->setNumTel($data['num_tel']);
        empty($data['adresse']) ? true : $cinema->setAdresse($data['adresse']);
        empty($data['email']) ? true : $cinema->setEmail($data['email']);
        empty($data['password']) ? true : $cinema->setPassword($data['password']);
        empty($data['image']) ? true : $cinema->setImage($data['image']);

        $cinema = $this->cinemaManager->persistCinemaDB($cinema);
    
         return $this->respond(['status' => Response::HTTP_OK , 'message' => 'Successfully Edited Cinema ' ,'data' => $cinema ] , 200);

    }


    /**
     * @Route("/delete/{id}", name="cinema_delete", methods={"GET" ,"DELETE"})
     */
    public function deleteAction(Request $request,  $id , CinemaRepository $cinemaRepositor): Response
    {

            $cinema = $this->cinemaRepo->findOneBy(['id' => $id]);
            if (empty($cinema)) 
            {
                return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
 
          $cinema = $this->cinemaManager->DeleteCinemaDB($cinema);

          return $this->respond(['status' => Response::HTTP_OK , 'message' => 'Successfully deleted Cinema ' ,'data' => $cinema ] , 200);

    }

}