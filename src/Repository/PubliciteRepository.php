<?php

namespace App\Repository;

use App\Entity\Publicite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Publicite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publicite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publicite[]    findAll()
 * @method Publicite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PubliciteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publicite::class);
    }

    // /**
    //  * @return Publicite[] Returns an array of Publicite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Publicite
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getPubsByEtat($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.etat = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    // nékhdhou ekher publicite mta3 pub user - 1
    public function getLastConfirmedPub() : ?Publicite // type de retoure w el ? kima traja3 pub kima tnajém matraja3ch
    {
        $pubs = $this->createQueryBuilder('p') // select from publicité as p
            ->andWhere('p.etat = :val')
            ->andWhere('p.date <= :da ')
            ->setParameter('da' , date("Y/m/d"))
            ->setParameter('val', "confirmed")
            ->orderBy('p.date' , 'DESC')
            ->getQuery()
            ->getResult();

        return $pubs != null ? $pubs[0] : null;
    }

    // tjib el pub el jéya w confirmé
    // el fonction hethi w elli 9baleha rien que bech ntalla3 bech ntalla3 el intevalle de jour elli howa libre fil calendrier
    public function getFirstConfirmedPendingPub() : ?Publicite
    {
        $pubs = $this->createQueryBuilder('p')
            ->andWhere('p.etat = :val')
            ->andWhere('p.date > :da ' )
            ->setParameter('val', "confirmed")
            ->setParameter('da' , date("Y/m/d"))
            ->orderBy('p.date' , 'ASC')
            ->getQuery()
            ->getResult();

        return $pubs != null ? $pubs[0] : null;
    }

    public function getCurrentPub() : ?Publicite
    {
        $pubs = $this->createQueryBuilder('p')
            ->andWhere('p.etat = :val')
            ->andWhere('p.date <= :da ' )
            ->andWhere('p.datefin >=  :da ' )
            ->setParameter('val', "confirmed")
            ->setParameter('da' , date("Y/m/d"))
            ->orderBy('p.date' , 'ASC')
            ->getQuery()
            ->getResult();

        return $pubs != null ? $pubs[0] : null;
    }


}
