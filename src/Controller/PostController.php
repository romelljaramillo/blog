<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PostController extends AbstractController
{
    private $http;

    public function __construct(HttpClientInterface $http)
    {
        $this->http = $http;
    }

    /**
     * @Route("/post/{id}", name="post", requirements={"id"="\d+"})
     */
    public function index(string $id): Response
    {
        $post = [];
        $user = [];

        $url_api = $this->getParameter('api_url') . '/posts/' . $id;
        $response = $this->http->request('GET', $url_api);
        $statusCode = $response->getStatusCode();
        
        if($statusCode == 200) {
            $post = $response->toArray();
            $user = $this->getUserApi($id);
        }

        return $this->render('post/index.html.twig', [
            'controller_name' => 'Post',
            'post' => $post,
            'user' => $user
        ]);
    }

    
    /**
     * returns user information
     *
     * @param string $id
     * @return array
     */
    public function getUserApi(string $id): array
    {
        $user = [];

        $url_api = $this->getParameter('api_url') . '/users/' .$id;
        $response = $this->http->request('GET', $url_api);
        $statusCode = $response->getStatusCode();

        if($statusCode == 200) {
            $user = $response->toArray();
            return $user;
        }

        return false;
    }

    /**
     * @Route("api/posts/{id}", name="posts", methods={"GET"})
     */
    public function getPostsApi(string $id): JsonResponse
    {
        $post = [];
        $user = [];

        $url_api = $this->getParameter('api_url') . '/posts/' . $id;
        $response = $this->http->request('GET', $url_api);
        $statusCode = $response->getStatusCode();
        
        if($statusCode == 200) {
            $post = $response->toArray();
            $user = $this->getUserApi($id);
            $post['user'] = $user;
        }

        return new JsonResponse($post, Response::HTTP_OK);
    }
}
