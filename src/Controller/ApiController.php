<?php

namespace App\Controller;


use FOS\RestBundle\Controller\AbstractFOSRestController;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Swagger\Annotations as SWG;
use Zmaglica\RickAndMortyApiWrapper\RickAndMortyApiWrapper;

class ApiController extends AbstractFOSRestController
{
    private $rickAndMortyApi;

    public function __construct()
    {
        $this->rickAndMortyApi = new RickAndMortyApiWrapper();

    }

    /**
     * Get character details
     * @SWG\Response(
     *     response="404",
     *     description="API Request URL doesn't exist"
     * )
     *
     * @Rest\Get("/api/character/{id}")
     *
     * @param Request $request
     * @param int $id
     * @return View
     */
    public function getCharacter(Request $request, int $id)
    {
        $characters = $this->rickAndMortyApi->character()->get($id);
        if ($characters->hasErrors()) {
            return $this->view($characters->toArray(), $characters->getResponseStatusCode());
        }
        $character = $characters->toArray();
        $character["origin"] = $characters->getOrigins()->toArray();
        $character["location"] = $characters->getLocations()->toArray();
        $character["episodes"] = $characters->getEpisodes()->toArray();
        return $this->view($character);
    }


    /**
     * List all characters from given dimension
     * @SWG\Parameter(
     *     in="query",
     *     type="number",
     *     minimum="1",
     *     name="page",
     *     description="Page number",
     *     default="1"
     * )
     * @SWG\Parameter(
     *     in="query",
     *     type="string",
     *     name="dimension",
     *     description="Dimension name",
     *     required=true,
     *     @SWG\Schema(
     *          type="string",
     *          example="Dimension C-137"
     *    )
     * )
     *
     * @SWG\Response(
     *     response="404",
     *     description="API Request URL doesn't exist"
     * )
     *
     * @Rest\Get("/api/character/dimension")
     *
     * @param Request $request
     * @return View
     */
    public function getAllCharactersFromGivenDimension(Request $request)
    {
        $dimension = $request->query->get('dimension');
        $page = (int)($request->query->get('page') ?? 1);
        $characters = $this->rickAndMortyApi->location()->setPage($page)->whereDimension($dimension)->getResidents();
        return $this->view($characters->toArray(), $characters->getResponseStatusCode());
    }

    /**
     * List all characters from given location id
     * @SWG\Parameter(
     *     in="query",
     *     type="number",
     *     minimum="1",
     *     name="page",
     *     description="Page number",
     *     default="1"
     * )
     *
     * @SWG\Response(
     *     response="404",
     *     description="API Request URL doesn't exist"
     * )
     *
     * @Rest\Get("/api/location/{id}/character/")
     *
     * @param Request $request
     * @param int $id
     * @return View
     */
    public function getAllCharactersFromGivenLocation(Request $request, int $id)
    {
        $page = (int)($request->query->get('page') ?? 1);
        $characters = $this->rickAndMortyApi->location()->setPage($page)->getResidents($id);
        return $this->view($characters->toArray(), $characters->getResponseStatusCode());
    }

    /**
     * List all characters from given location name
     * @SWG\Parameter(
     *     in="query",
     *     type="number",
     *     minimum="1",
     *     name="page",
     *     description="Page number",
     *     default="1"
     * )
     *
     * @SWG\Parameter(
     *     in="query",
     *     type="string",
     *     name="name",
     *     description="Location name",
     *     required=true,
     *     @SWG\Schema(
     *          type="string",
     *          example="Earth"
     *    )
     * )
     *
     * @SWG\Response(
     *     response="404",
     *     description="API Request URL doesn't exist"
     * )
     *
     * @Rest\Get("/api/location/character/")
     *
     * @param Request $request
     * @param int $id
     * @return View
     */
    public function getAllCharactersFromGivenLocationName(Request $request)
    {
        $name = $request->query->get('name');
        $page = (int)($request->query->get('page') ?? 1);
        $characters = $this->rickAndMortyApi->location()->setPage($page)->whereName($name)->getResidents();
        return $this->view($characters->toArray(), $characters->getResponseStatusCode());
    }

    /**
     * List all characters from given episode id
     * @SWG\Parameter(
     *     in="query",
     *     type="number",
     *     minimum="1",
     *     name="page",
     *     description="Page number",
     *     default="1"
     * )
     *
     * @SWG\Response(
     *     response="404",
     *     description="API Request URL doesn't exist"
     * )
     *
     * @Rest\Get("/api/episode/{id}/character/")
     *
     * @param Request $request
     * @param int $id
     * @return View
     */
    public function getAllCharactersFromGivenEpisode(Request $request, int $id)
    {
        $page = (int)($request->query->get('page') ?? 1);
        $characters = $this->rickAndMortyApi->episode()->setPage($page)->getCharacters($id);
        return $this->view($characters->toArray(), $characters->getResponseStatusCode());
    }
}