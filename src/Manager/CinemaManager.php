<?php

namespace App\Manager;

use App\Entity\Cinema;
use Doctrine\ORM\EntityManagerInterface;


class CinemaManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;



    public function __construct(
        EntityManagerInterface $em
      )
    {
        $this->em = $em;
      
    }

    /**
     * @param Cinema $cinema
     */
    public function persistCinemaDB(Cinema $cinema)
    {
        if ($cinema instanceof Cinema) {
            $this->em->persist($cinema);
            $this->em->flush();

            return $cinema;

           
        }
    }


    /**
     * Delete Cinema By Id
     */
    public function DeleteCinemaDB(Cinema $cinema)
    {
        if ($cinema instanceof Cinema) {
            $this->em->remove($cinema);
            $this->em->flush();

            return $cinema;

           
        }

    }
}