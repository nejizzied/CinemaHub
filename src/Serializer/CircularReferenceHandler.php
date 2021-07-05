<?php

namespace App\Serializer;

use App\Entity\Cinema;
use App\Entity\Film;
use App\Entity\SalleDeProjection;
use App\Entity\Categorie;
use Symfony\Component\Routing\RouterInterface;

class CircularReferenceHandler
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    
    public function __invoke($object)
    {

        return $object->getId();
    }
}