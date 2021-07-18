<?php

namespace App\Controller;
use App\Entity\Cinema;
use App\Form\PublType;
use App\Form\UpdateType;
use App\Entity\Publicite;


use App\Repository\AdminRepository;
use App\Repository\CinemaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PubliciteRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PubController extends AbstractController
{
    /**
     * @Route("/api/publicite/pub", name="pub")
     */
    public function index(PubliciteRepository $publiciteRepository ,
       NormalizerInterface $Normalizer
    ): Response
    {
        $pub = $publiciteRepository->getCurrentPub();
        $jsonContent = $Normalizer->normalize($pub, 'json',['groups' => 'read' , 'enable_max_depth' => true]);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }

    /**
     * @Route("/api/publicites/AjoutPub", name="AjoutPub" , methods= {"post"})
     */
    public function AjoutPub(Request $request,
                             PubliciteRepository $publiciteRepository
        , EntityManagerInterface $em
        , \Swift_Mailer $mailer
        , CinemaRepository $cinemaRepository
        , NormalizerInterface $Normalizer
    ): Response //Requestion de HTTP FONDATION , CTRL+ESPACE afin d'autocomplet
    {
        $dp=new Publicite();
        $data = json_decode($request->getContent(), true);
        empty($data['prix']) ? true : $dp->setPrix($data['prix']);
        empty($data['idCinema']) ? true : $dp->setIdCinema($cinemaRepository->find($data['idCinema']));
        empty($data['date']) ? true : $dp->setDate($data['date']);
        empty($data['dateFin']) ? true : $dp->setDateFin($data['dateFin']);
        $dp->setEtat('en cours de traitement');
        $em ->persist($dp);
        $em ->flush();
        $message = (new \Swift_Message('Demande de pub'))
            ->setFrom('serviceclient619@gmail.com')
            ->setTo('serviceclient619@gmail.com')
            ->setBody('Le Cinéma '.$dp->getIdCinema()->getNomCinema().'a demandé une une pub merci de répondre a cette demande rapidement !')
        ;

        $mailer->send($message);
        $jsonContent = $Normalizer->normalize(['msg' => "Demande envoyé"]);
        $retour=json_encode($jsonContent);
        return new Response($retour);

    }                                                     

    /**
     * @Route("/api/publicites/affichagePub", name="affichagePub")
     */
    public function afficherPub(PubliciteRepository $rep , NormalizerInterface $Normalizer ): Response
    {
        $list=$rep->findAll();
        $jsonContent = $Normalizer->normalize($list ,'json',['groups' => 'read' , 'enable_max_depth' => true]);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }

    /**
     * @Route("/api/publicites/ProlongerPub/{id}", name="ProlongerPub" , methods = {"post"})
     */
    public function ProlongerPub (PubliciteRepository $rep,$id,Request $request , EntityManagerInterface $em
    ,  \Swift_Mailer $mailer
    ,  NormalizerInterface $Normalizer,
    CinemaRepository $cinemaRepository
    ): Response //Request de HTTP FONDATION , CTRL+ESPACE afin d'autocomplet
    {

        $dp=new Publicite();
        $dp=$rep->find($id);
        $data = json_decode($request->getContent(), true);
        empty($data['prix']) ? true : $dp->setPrix($data['prix']);
        empty($data['idCinema']) ? true : $dp->setIdCinema($cinemaRepository->find($data['idCinema']));
        empty($data['date']) ? true : $dp->setDate($data['date']);
        empty($data['dateFin']) ? true : $dp->setDateFin($data['dateFin']);
        $dp->setEtat('Demande de Prolongation');
        $em ->persist($dp);
        $em ->flush();
        $message = (new \Swift_Message('Demande de Prolongation de pub'))
                ->setFrom('serviceclient619@gmail.com')
                ->setTo('serviceclient619@gmail.com')
                ->setBody('Le Cinéma '.$dp->getIdCinema()->getNomCinema().'a demandé une prologation une pub à la date merci de répondre a cette demande rapidement !')
            ;

            $mailer->send($message);
        $jsonContent = $Normalizer->normalize(['msg' => "publicité modifié"]);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }


    /**
     * @Route("/api/publicites//DelPub/{id}", name="DelPub")
     */
    public function DELPub ($id,Request $request
        ,  NormalizerInterface $Normalizer)
    {
        $Publicite=$this->getDoctrine()
        ->getRepository(Publicite::class)
        ->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Publicite);
        $em ->flush();
        $jsonContent = $Normalizer->normalize(['msg' => "publicité suprimé"]);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }

    /**
     * @Route("/api/publicites/ConfirmerPub/{id}", name="ConfirmerPub")
     */
    public function ConfirmerPub (PubliciteRepository $rep,$id,Request $request ,
                                  \Swift_Mailer $mailer ,
                                  NormalizerInterface $Normalizer,
                                  AdminRepository $adminRepository,
                                  EntityManagerInterface $em): Response //Request de HTTP FONDATION , CTRL+ESPACE afin d'autocomplet
    {
        $data = json_decode($request->getContent(), true);

        $dp=new Publicite();
        $dp=$rep->find($id);
        $dp->setEtat('confirmed') ;
        empty($data['idAdmin']) ? true : $dp->setIdAdmin($adminRepository->find($data['idAdmin']));
        $em ->persist($dp);
        $em ->flush();

        $message = (new \Swift_Message('Demande de Pub Accepter'))
            ->setFrom('serviceclient619@gmail.com')
            ->setTo($dp->getIdCinema()->getEmail())
            ->setBody('votre demande de pub a été accepté avec succées !')
        ;

        $mailer->send($message);

        $jsonContent = $Normalizer->normalize(['msg' => "publicité confirmé"]);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }


    /**
     * @Route("/api/publicites/AnnulerPub/{id}", name="AnnulerPub")
     */
    public function AnnulerPub (PubliciteRepository $rep,$id,Request $request ,
                                NormalizerInterface $Normalizer,
                                AdminRepository $adminRepository,
                                 EntityManagerInterface $em): Response //Request de HTTP FONDATION , CTRL+ESPACE afin d'autocomplet
    {
        $data = json_decode($request->getContent(), true);

        $dp=new Publicite();
        $dp=$rep->find($id);
        $dp->setEtat('canceled') ;

        empty($data['idAdmin']) ? true : $dp->setIdAdmin($adminRepository->find($data['idAdmin']));

        $em ->persist($dp);
        $em ->flush();
        $jsonContent = $Normalizer->normalize(['msg' => "publicité annulé"]);
        $retour=json_encode($jsonContent);
        return new Response($retour);

    }
    
    /**
     * @Route("/api/publicites/cinema/{id}", name="affichagePubCinema")
     */
    public function affichageCinemaPub( Request $request , $id, PubliciteRepository $rep
        ,  \Swift_Mailer $mailer
        , CinemaRepository $cinemaRepository ,
                                        EntityManagerInterface $em ,  NormalizerInterface $Normalizer) : Response
    {

        $data = json_decode($request->getContent(), true);

        $cinema = new Cinema();
        empty($data['idCinema']) ? true : $cinema = $cinemaRepository->find($id);

        $list=$rep->findBy(['idCinema' => $cinema]);
        $dateToNotify = new \DateTime() ;

        // boucle sur tt les publicite kén l9a wa7da date mté3ha wfét i7otélha el etat expiré
        foreach ($list as $l)
        {
            if($dateToNotify == $l->getDateFin())
            {
                $l->setEtat('expired') ;
                $em ->persist($l);
            }
        }
        $em ->flush();
        // itha kén tawa el date mt23ha 9orbet b 2 jours yab3éth mail
        $dateToNotify->modify('2 day');
        $l = new Publicite();
        foreach ($l as $list)
        {
            if($dateToNotify == $l->getDateFin())
            {
                $message = (new \Swift_Message('Your Pub is going to expire'))
                    ->setFrom('serviceclient619@gmail.com')
                    ->setTo($l->getIdCinema()->getEmail())
                    ->setBody('votre pub va etre expiré merci de prolongé votre pub !');
                $mailer->send($message);
            }
        }

        $jsonContent = $Normalizer->normalize($list,'json',['groups' => 'read' , 'enable_max_depth' => true]);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }


    // fonction qui nous donne le calendrier : exemple na3teha 01/02/2021 w 03/02/2021 traja3li ['01/02/2021' , '02/02/2021' , '03/02/2021' ]
    private function createDateRangeArray($strDateFrom,$strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange = [];

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }

}