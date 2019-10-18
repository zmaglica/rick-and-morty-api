<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;

class EpisodeController extends BaseApiController
{
    /**
     * List all characters from given episode id
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
     * @SWG\Response(
     *     response="400",
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Data not found or API endpoint doesn't exist"
     * )
     * @SWG\Tag(name="Episode")
     *
     * @Rest\Get("/api/episode/{id}/character/")
     *
     * @param ParamFetcher $paramFetcher
     * @param int $id
     * @return View
     */
    public function getAllCharactersFromGivenEpisode(ParamFetcher $paramFetcher, int $id)
    {
        $page = $paramFetcher->get('page');
        $characters = $this->rickAndMortyApi->episode()->setPage($page)->getCharacters($id);

        return $this->view($characters->toArray(), $characters->getResponseStatusCode());
    }
}
