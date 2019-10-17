<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Zmaglica\RickAndMortyApiWrapper\RickAndMortyApiWrapper;

class BaseApiController extends AbstractFOSRestController
{
    protected $rickAndMortyApi;

    public function __construct(RickAndMortyApiWrapper $rickAndMortyApi)
    {
        $this->rickAndMortyApi = $rickAndMortyApi;
    }
}
