<?php

namespace App\Controller;


use FOS\RestBundle\Controller\AbstractFOSRestController;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class LocationController extends BaseApiController
{

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
     *     description="Data not found or API endpoint doesn't exist"
     * )
     * @SWG\Tag(name="Location")
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
     *     description="Data not found or API endpoint doesn't exist"
     * )
     * @SWG\Tag(name="Location")
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

}