<?php

declare(strict_types=1);

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiController extends AbstractFOSRestController
{
   
    protected function respond($data, int $statusCode = Response::HTTP_OK): Response
    {
        return $this->handleView($this->view($data, $statusCode));
    }

    protected function CatchError($data, int $statusCode = Response::HTTP_OK): Response
    {
        return $this->handleView($this->view($data, $statusCode));
    }
}