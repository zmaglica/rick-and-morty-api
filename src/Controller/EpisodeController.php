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

class EpisodeController extends BaseApiController
{

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
     *     description="Data not found or API endpoint doesn't exist"
     * )
     * @SWG\Tag(name="Episode")
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