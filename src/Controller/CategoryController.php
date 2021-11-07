<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CategoryController extends AbstractController
{

    /**
     * @Route("/category", name="category")
     */
    public function index(HttpClientInterface $http): Response
    {
        $posts = [];
        $array_post = [];

        $url_api = $this->getParameter('api_url') . '/posts';
        $response = $http->request('GET', $url_api);
        $statusCode = $response->getStatusCode();

        if($statusCode == 200) {
            $posts = $response->toArray();
        }

        return $this->render('category/index.html.twig', [
            'controller_name' => 'All Posts',
            'posts' => $posts
        ]);
    }

    /**
     * @Route("api/category", name="category", methods={"GET"})
     */
    public function getPostsApi(HttpClientInterface $http): JsonResponse
    {
        $posts = [];
        $array_post = [];

        $url_api = $this->getParameter('api_url') . '/posts';
        $response = $http->request('GET', $url_api);
        $statusCode = $response->getStatusCode();

        if($statusCode == 200) {
            $posts = $response->toArray();
        }

        return new JsonResponse($posts, Response::HTTP_OK);
    }

}
