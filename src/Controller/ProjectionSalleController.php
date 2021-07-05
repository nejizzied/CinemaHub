<?php


declare(strict_types=1);

namespace App\Controller;

use App\Entity\SalleDeProjection;
use App\Repository\CinemaRepository;
use App\Repository\SalleDeProjectionRepository;
use App\Repository\FilmRepository;
use App\Manager\ProjectionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/projection/v1", name="projection")
 */
class ProjectionSalleController extends AbstractApiController
{

    private $projectionRepo ;
    private $projectionManager ;
    private $cinemarepository ;
    private $FilmRepository ;

    /**
     * Constructeur For Injection Dependency
     */

     public function __construct(SalleDeProjectionRepository $projectionRepository , ProjectionManager $projectionManager , CinemaRepository $CinemaRepository , FilmRepository $FilmRepository)
     {
          $this->projectionRepo =  $projectionRepository ;
          $this->projectionManager = $projectionManager ;
          $this->cinemarepository = $CinemaRepository ;
          $this->FilmRepository = $FilmRepository ;
     }
 
    /**
     * @Route("/", name="AllSaleProjection", methods={"GET"})
     */
    public function getAllSalleProjectionAction()
    {

       if (!$this->projectionRepo->findAll()) {
        return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'List Empty '], Response::HTTP_INTERNAL_SERVER_ERROR);
     
    }
   
   return $this->respond(['status' => Response::HTTP_OK , 'message' => ' Availaible Salle Projection ' ,'data' => $this->projectionRepo->findAll() ] , 200);


    }

    /**
     * @Route("/new", name="salleProjection_new", methods={"POST"})
     */
    public function CreateAction(Request $request, SerializerInterface $serializer)
    {
        
        try {
            $salle = new SalleDeProjection() ;
            $data = json_decode($request->getContent(), true);

            empty($data['cinema']) ? true : $cinema = $this->cinemarepository->find($data['cinema']);
            empty($data['film']) ? true : $film = $this->FilmRepository->find($data['film']);
            empty($data['nbr_place']) ? true : $salle->setNbrPlaces($data['nbr_place']);
            empty($data['image']) ? true : $salle->setImage($data['image']);
            empty($data['status']) ? true : $salle->setStatus($data['status']);
     

              if(!isset($cinema))
              {
                return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'Please Create a cinema '], Response::HTTP_INTERNAL_SERVER_ERROR);
     
              }
              if(!isset($film))
              {
                return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'Please Create a movie '], Response::HTTP_INTERNAL_SERVER_ERROR);
     
              }
            $salle->setIdCinema($cinema);
            $salle->setIdFilm($film);

           
            $salle = $this->projectionManager->persistSalleDB($salle);

            return $this->respond(['status' => Response::HTTP_OK , 'message' => ' Salle Projection created successful ' ,'data' => $salle ] , 200);


        } catch (NotEncodableValueException $e) {
            
            return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'Something went wrong '], Response::HTTP_INTERNAL_SERVER_ERROR);
     
        }
       
    }

    /**
     * @Route("/Show/{id}", name="projection_show", methods={"GET"})
     */
    public function showAction($id , SerializerInterface $serialize)
    {

        $salle = $this->getDoctrine()->getRepository(SalleDeProjection::class)->find($id);

        if (!$salle) {
            return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'Salle Not found'], Response::HTTP_INTERNAL_SERVER_ERROR);
         
        }

        return $this->respond(['status' => Response::HTTP_OK , 'message' => 'details for Salle Projection' ,'data' => $salle ] , 200);

    }

    /**
     * @Route("/{id}/edit", name="projection_edit", methods={"GET","PUT"})
     */
    public function editAction(Request $request, $id): Response
    {
  
    
        $salle = $this->projectionRepo->findOneBy(['id' => $id]);
        if (empty($salle)) {
         
               return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'Salle projection not found'], Response::HTTP_INTERNAL_SERVER_ERROR);
         
        }

        $data = json_decode($request->getContent(), true);

        empty($data['cinema']) ? true : $cinema = $this->cinemarepository->find($data['cinema']);
        empty($data['film']) ? true : $film = $this->FilmRepository->find($data['film']);
        empty($data['nbr_place']) ? true : $salle->setNbrPlaces($data['nbr_place']);
        empty($data['image']) ? true : $salle->setImage($data['image']);
        empty($data['status']) ? true : $salle->setStatus($data['status']);
 

          if(!isset($cinema))
          {
            return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'Please Create a cinema '], Response::HTTP_INTERNAL_SERVER_ERROR);
 
          }
          if(!isset($film))
          {
            return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'Please Create a movie '], Response::HTTP_INTERNAL_SERVER_ERROR);
 
          }
        $salle->setIdCinema($cinema);
        $salle->setIdFilm($film);

        $salle = $this->projectionManager->persistSalleDB($salle);

        return $this->respond(['status' => Response::HTTP_OK , 'message' => 'Projection Salle Updated successful' ,'data' => $salle ] , 200);


    }

    /**
     * @Route("/{id}/delete", name="salleprojection_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request , $id ): Response
    {
       

            $salle = $this->projectionRepo->find($id);

             
            if (empty($salle)) 
            {
                return $this->CatchError([ 'status'=> Response::HTTP_NOT_FOUND ,'message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
 
          $salle = $this->projectionManager->DeleteSalleDB($salle);
          return $this->respond(['status' => Response::HTTP_OK , 'message' => 'Projection Deleted successful ' ,'data' => $salle ] , 200);


           
    }
}
