<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class CharacterController extends BaseApiController
{
    /**
     * List all characters from given dimension
     *
     * @QueryParam(name="dimension", strict=true, nullable=false, description="Dimension name")
     * @QueryParam(name="page", strict=true, nullable=true, requirements="\d+", default="1", description="Page of the request.")
     *
     *
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
     *     response="400",
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Data not found or API endpoint doesn't exist"
     * )
     * @SWG\Tag(name="Character")
     *
     * @Rest\Get("/api/character/dimension")
     *
     * @param ParamFetcher $paramFetcher
     * @return View
     */
    public function getAllCharactersFromGivenDimension(ParamFetcher $paramFetcher)
    {
        $dimension = $paramFetcher->get('dimension');
        $page = $paramFetcher->get('page');
        $characters = $this->rickAndMortyApi->location()->setPage($page)->whereDimension($dimension)->getResidents();

        return $this->view($characters->toArray(), $characters->getResponseStatusCode());
    }

    /**
     * Get character statistics (total alive, dead, unknown status characters, total female, male, genderless and unknown gender characters
     *
     * @SWG\Response(
     *     response="400",
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Data not found or API endpoint doesn't exist"
     * )
     * @SWG\Tag(name="Character")
     *
     * @Rest\Get("/api/character/statistics")
     *
     * @return View
     */
    public function getCharacterStatistic()
    {
        $characterApi = $this->rickAndMortyApi->character();
        $statistic = [
            'total' => [
                'status' => [
                    'alive' => $characterApi->clear()->isAlive()->get()->count(),
                    'dead' => $characterApi->clear()->isDead()->get()->count(),
                    'unknown' => $characterApi->isStatusUnknown()->get()->count(),
                ],
                'gender' => [
                    'female' => $characterApi->clear()->isFemale()->get()->count(),
                    'male' => $characterApi->clear()->isMale()->get()->count(),
                    'genderLess' => $characterApi->clear()->isGenderless()->get()->count(),
                    'genderUnknown' => $characterApi->clear()->isGenderUnknown()->get()->count(),
                ],
            ],
        ];

        return $this->view($statistic);
    }

    /**
     * Get character details
     *
     * @SWG\Response(
     *     response="400",
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Data not found or API endpoint doesn't exist"
     * )
     * @SWG\Tag(name="Character")
     *
     * @Rest\Get("/api/character/{id}")
     *
     * @param int $id
     * @return View
     */
    public function getCharacter(int $id)
    {
        $characters = $this->rickAndMortyApi->character()->get($id);
        if ($characters->hasErrors()) {
            return $this->view($characters->toArray(), $characters->getResponseStatusCode());
        }
        $character = $characters->toArray();
        $character['origin'] = $characters->getOrigins()->toArray();
        $character['location'] = $characters->getLocations()->toArray();
        $character['episodes'] = $characters->getEpisodes()->toArray();

        return $this->view($character);
    }
}
