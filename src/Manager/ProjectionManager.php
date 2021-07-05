<?php

namespace App\Manager;

use App\Entity\SalleDeProjection;
use Doctrine\ORM\EntityManagerInterface;


class ProjectionManager
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
     * @param SalleDeProjection $SalleDeProjection
     */
    public function persistSalleDB(SalleDeProjection $SalleDeProjection)
    {
        if ($SalleDeProjection instanceof SalleDeProjection) {
            $this->em->persist($SalleDeProjection);
            $this->em->flush();

            return $SalleDeProjection;

           
        }
    }


    /**
     * Delete SalleDeProjection By Id
     */
    public function DeleteSalleDB(SalleDeProjection $SalleDeProjection)
    {
        if ($SalleDeProjection instanceof SalleDeProjection) {
            $this->em->remove($SalleDeProjection);
            $this->em->flush();

            return $SalleDeProjection;

           
        }

    }
}