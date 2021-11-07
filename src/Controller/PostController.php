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
     * @Route("/posts", name="posts")
     */
    public function index(): Response
    {
        $posts = [];
        $array_post = [];

        $url_api = $this->getParameter('api_url') . '/posts';
        $response = $this->http->request('GET', $url_api);
        $statusCode = $response->getStatusCode();

        if($statusCode == 200) {
            $posts = $response->toArray();
        }

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/posts/{id}", name="post", requirements={"id"="\d+"})
     */
    public function getPost(string $id): Response
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

        return $this->render('post/post.html.twig', [
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
     * @Route("/api/posts", name="api_posts", methods={"GET"})
     */
    public function getPostsApi(): JsonResponse
    {
        $posts = [];
        $array_post = [];

        $url_api = $this->getParameter('api_url') . '/posts';
        $response = $this->http->request('GET', $url_api);
        $statusCode = $response->getStatusCode();

        if($statusCode == 200) {
            $posts = $response->toArray();
        }

        return new JsonResponse($posts, Response::HTTP_OK);
    }

    /**
     * @Route("/api/posts/{id}", name="api_post", methods={"GET"})
     */
    public function getPostApi(string $id): JsonResponse
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
