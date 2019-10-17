<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;

class LocationController extends BaseApiController
{
    /**
     * List all characters from given location id
     *
     * @QueryParam(name="page", strict=true, nullable=true, requirements="\d+", default="1", description="Page of the request.")
     *
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
     *     description="Data not found or API endpoint doesn't exist"
     * )
     * @SWG\Tag(name="Location")
     *
     * @Rest\Get("/api/location/{id}/character/")
     *
     * @param ParamFetcher $paramFetcher
     * @param int $id
     * @return View
     */
    public function getAllCharactersFromGivenLocation(ParamFetcher $paramFetcher, int $id)
    {
        $page = $paramFetcher->get('page');
        $characters = $this->rickAndMortyApi->location()->setPage($page)->getResidents($id);

        return $this->view($characters->toArray(), $characters->getResponseStatusCode());
    }

    /**
     * List all characters from given location name
     *
     * @QueryParam(name="name", strict=true, nullable=false, description="Location name")
     * @QueryParam(name="page", strict=true, nullable=true, requirements="\d+", default="1", description="Page of the request.")
     *
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
     *     description="Data not found or API endpoint doesn't exist"
     * )
     * @SWG\Tag(name="Location")
     *
     * @Rest\Get("/api/location/character/")
     *
     * @param ParamFetcher $paramFetcher
     * @return View
     */
    public function getAllCharactersFromGivenLocationName(ParamFetcher $paramFetcher)
    {
        $name = $paramFetcher->get('name');
        $page = $paramFetcher->get('page');
        $characters = $this->rickAndMortyApi->location()->setPage($page)->whereName($name)->getResidents();

        return $this->view($characters->toArray(), $characters->getResponseStatusCode());
    }
}
