<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(HttpClientInterface $client): Response
    {
        $posts = [];
        $response = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts');
        $statusCode = $response->getStatusCode();

        if($statusCode == 200) {
            // dump($response->toArray());
            $posts = $response->toArray();
        }
        // dump($response->getContent());
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'posts' => $posts
        ]);
    }
}
