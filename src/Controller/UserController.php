<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserController extends AbstractController
{
    private $http;

    public function __construct(HttpClientInterface $http)
    {
        $this->http = $http;
    }

    /**
     * @Route("/users", name="users")
     */
    public function index(): Response
    {
        $users = [];

        $url_api = $this->getParameter('api_url') . '/users';
        $response = $this->http->request('GET', $url_api);
        $statusCode = $response->getStatusCode();

        if($statusCode == 200) {
            $users = $response->toArray();
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users
        ]);
    }

    /**
     * @Route("/users/{id}", name="user", requirements={"id"="\d+"})
     */
    public function getUserById(string $id): Response
    {
        $user = [];

        if($id) {
            $url_api = $this->getParameter('api_url') . '/users/' .$id;
            $response = $this->http->request('GET', $url_api);
            $statusCode = $response->getStatusCode();

            if($statusCode == 200) {
                $user = $response->toArray();
            }
        }

        return $this->render('user/viewuser.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user
        ]);
    }

    /**
     * @Route("api/users/{id}", name="user", methods={"GET"})
     */
    public function getUserByIdApi(string $id): JsonResponse
    {
        $user = [];

        if($id) {
            $url_api = $this->getParameter('api_url') . '/users/' .$id;
            $response = $this->http->request('GET', $url_api);
            $statusCode = $response->getStatusCode();

            if($statusCode == 200) {
                $user = $response->toArray();
            }
        }
        return new JsonResponse($user, Response::HTTP_OK);
    }

}
