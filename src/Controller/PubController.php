<?php

namespace App\Controller;
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
     * @Route("/pub", name="pub")
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
     * @Route("/api/AjoutPub", name="AjoutPub")
     */
    public function AjoutPub(Request $request,
        PubliciteRepository $publiciteRepository
        , EntityManagerInterface $em
    , \Swift_Mailer $mailer
    , CinemaRepository $cinemaRepository
    , NormalizerInterface $Normalizer
    ): Response //Requestion de HTTP FONDATION , CTRL+ESPACE afin d'autocomplet
    {

        // get all pub confirmed afin de modifier le calendrier : itha ken date tekhdhét twalli gris fil calendrier
        $pubs = $publiciteRepository->getPubsByEtat("confirmed");

        if( $publiciteRepository->getLastConfirmedPub() != null
            && $publiciteRepository->getLastConfirmedPub()->getDateFin() >= new \DateTime() )
        {
            // kén famma pub 9bal w el date fin mté3ha fétet lyouma nékhdhou el date fin mté3ha
            $dateStart =   $publiciteRepository->getLastConfirmedPub()->getDateFin() ;
        }
        else
        {
            // sinon nékhdhou date lyouma kén mafamech pub 9dima jémla
            $dateStart = new \DateTime() ;
        }

        $dateStart->modify("1 day"); // nzidou nhar


        $maxDate = new \DateTime($dateStart->format('Y-m-d')) ;
        $maxDate->modify("3 month"); // preciser la date maximale d'une publicite = 3 mois

        // ki yébda 3anna publicité prévu ( jéya )
        if($publiciteRepository->getFirstConfirmedPendingPub() != null)
        {
            // nekhedhou el debut mta3 el pub prévu
            $dateEnd = $publiciteRepository->getFirstConfirmedPendingPub()->getDate() ;
        }
        else
        {
            // nekhedhou el date max
            $dateEnd = $maxDate ;
        }

        $dateEnd->modify("-1 day"); // lézemna nékhedhou el date debut mta3 el pub prévu -1

        $dateDisabledArray = $this->createDateRangeArray( $dateStart->format('Y-m-d') ,$dateEnd->format('Y-m-d'));

        $d1 = new \DateTime('2021-07-01') ;
        $d2 = new \DateTime('2021-07-07') ;
        $dateJcc = $this->createDateRangeArray( $d1->format('Y-m-d') ,$d2->format('Y-m-d'));


        $pub= new Publicite();
        // $user = $this->get('security.token_storage')->getToken()->getUser(); // get connected user
        $pub->setIdCinema($cinemaRepository->findAll()[1]); // get connected user but it's statique

        $form=$this->createForm(PublType::class,$pub);
        $form->handleRequest($request);

        // initialiser le prix
        $pub->setPrix(0);

        if($form->isSubmitted()){

                $pub->setEtat("Demande en cours de traitement");
                $em ->persist($pub);
                $em ->flush();

            /*  PARTIE MAIL user ya3ml demande admin ijih mail
              $message = (new \Swift_Message('Demande de Pub'))
                ->setFrom('serviceclient619@gmail.com')
                ->setTo('serviceclient619@gmail.com')
                ->setBody('Le Cinéma '.$pub->getIdCinema()->getNomCinema().'a demandé une pub à la date merci de répondre a cette demande rapidement !')
            ;

            $mailer->send($message);
*/

            return $this->redirectToRoute('affichagePubCinema');

        }
        return $this->render('pub/add.html.twig', [
            'f' => $form ->createView(), // formulaire
            'invalidDates' => $dateDisabledArray , // naba3thou fil les date ta3 el calendrier
            'jccDate' => $dateJcc // b3ath el tablo jcc lil view
        ]);
    }

/**
     * @Route("/affichagePub", name="affichagePub")
     */
    public function affichage(PubliciteRepository $rep): Response
    {
       $list=$rep->findAll();

        return $this->render('pub/aff.html.twig', [
            'list' =>$list
        ]);
    }


/**
     * @Route("/ModifierPub/{id}", name="ModifierPub")
     */
    public function ModifierPub (PubliciteRepository $rep,$id,Request $request , EntityManagerInterface $em
    ,  \Swift_Mailer $mailer
    ): Response //Request de HTTP FONDATION , CTRL+ESPACE afin d'autocomplet
    {

        $pubs = $rep->getPubsByEtat("confirmed");
        $dateStart = $rep->getLastConfirmedPub() != null && $rep->getLastConfirmedPub()->getDateFin() >= new \DateTime() ? $publiciteRepository->getLastConfirmedPub()->getDateFin() : new \DateTime();
        $dateStart->modify("1 day");


        $maxDate = new \DateTime($dateStart->format('Y-m-d')) ;
        $maxDate->modify("3 month");

        $dateEnd = $rep->getFirstConfirmedPendingPub() != null ? $rep->getFirstConfirmedPendingPub()->getDate() : $maxDate ;
        $dateEnd->modify("-1 day");

        dump($dateStart);
        dump($dateEnd);

        $dateDisabledArray = $this->createDateRangeArray( $dateStart->format('Y-m-d') ,$dateEnd->format('Y-m-d'));

        // definition intervale mta3 ayamet el jcc
        $d1 = new \DateTime('2021-07-01') ; // debut jcc
        $d2 = new \DateTime('2021-07-07') ; // fin jcc
        $dateJcc = $this->createDateRangeArray( $d1->format('Y-m-d') ,$d2->format('Y-m-d'));



        $dp=new Publicite();
        $dp=$rep->find($id);
        $form=$this->createForm(UpdateType::class,$dp);
        $form->handleRequest($request);
        if($form ->isSubmitted()){
            $dp->setEtat('Demande de Prolongation');
            $em ->persist($dp);
            $em ->flush();


            $message = (new \Swift_Message('Demande de Prolongation de pub'))
                ->setFrom('serviceclient619@gmail.com')
                ->setTo('serviceclient619@gmail.com')
                ->setBody('Le Cinéma '.$pub->getIdCinema()->getNomCinema().'a demandé une prologation une pub à la date merci de répondre a cette demande rapidement !')
            ;

            $mailer->send($message);


            return $this->redirectToRoute('affichagePubCinema');
        }
        $list=$rep->findAll();
        return $this->render('pub/UpdatePub.html.twig', [
            'f' => $form->createView(), 'invalidDates' => $dateDisabledArray ,
            'jccDate' => $dateJcc
        ]);
    }


    /**
     * @Route("/DELPub/{id}", name="DELPub")
     */
    public function DELPub ($id,Request $request )
    {
        $Publicite=$this->getDoctrine()
        ->getRepository(Publicite::class)
        ->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Publicite);
        $em ->flush();
            return $this->redirectToRoute('affichagePub');
    


    }



    /**
     * @Route("/affc", name="affc")
     */
    public function affc(EntityManagerInterface $em
    ,  \Swift_Mailer $mailer
    ): Response //Request de HTTP FONDATION , CTRL+ESPACE afin d'autocomplet
    {
        $result=$em->createQuery("Select * from publicite ");
        $dp=new Publicite();
        $dp=$rep->find($id);
        $form=$this->createForm(UpdateType::class,$dp);
        $form->handleRequest($request);
        if($form ->isSubmitted()){
            $em ->persist($dp);
            $em ->flush();
            return $this->redirectToRoute('affichagePub');
        }
        $list=$rep->findAll();
        return $this->render('pub/UpdatePub.html.twig', [
            'f' => $form->createView(),
        ]);
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

    /**
     * @Route("/ConfirmerPub/{id}", name="ConfirmerPub")
     */
    public function ConfirmerPub (PubliciteRepository $rep,$id,Request $request ,
          \Swift_Mailer $mailer ,

                                  AdminRepository $adminRepository,
                                 EntityManagerInterface $em): Response //Request de HTTP FONDATION , CTRL+ESPACE afin d'autocomplet
    {
        $dp=new Publicite();
        $dp=$rep->find($id);

        $dp->setEtat('confirmed') ;
        $dp->setIdAdmin($adminRepository->findAll()[0]); // get connceted user but it's static now
        $em ->persist($dp);
        $em ->flush();


        $message = (new \Swift_Message('Demande de Pub Accepter'))
            ->setFrom('serviceclient619@gmail.com')
            ->setTo($dp->getIdCinema()->getEmail())
            ->setBody('votre demande de pub a été accepté avec succées !')
        ;

        $mailer->send($message);


        return $this->redirectToRoute('affichagePub');

    }


    /**
     * @Route("/AnnulerPub/{id}", name="AnnulerPub")
     */
    public function AnnulerPub (PubliciteRepository $rep,$id,Request $request ,
                                 AdminRepository $adminRepository,
                                 EntityManagerInterface $em): Response //Request de HTTP FONDATION , CTRL+ESPACE afin d'autocomplet
    {
        $dp=new Publicite();
        $dp=$rep->find($id);

        $dp->setEtat('canceled') ;
        $dp->setIdAdmin($adminRepository->findAll()[0]); // get connceted user but it's static now
        $em ->persist($dp);
        $em ->flush();

        return $this->redirectToRoute('affichagePub');

    }
    
    /**
     * @Route("/affichagePubCinema", name="affichagePubCinema")
     */
    public function affichageCinemaPub(PubliciteRepository $rep
        ,  \Swift_Mailer $mailer
        , CinemaRepository $cinemaRepository , EntityManagerInterface $em): Response
    {
        // $user = $this->get('security.token_storage')->getToken()->getUser(); // get connected user
        $list=$rep->findBy(['id_cinema' => $cinemaRepository->findAll()[1]]); // get connected user but it's statique
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

        return $this->render('pub/cinemaAffPub.html.twig', [
            'list' =>$list
        ]);
    }
}