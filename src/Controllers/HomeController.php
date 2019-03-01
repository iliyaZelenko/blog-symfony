<?php

namespace App\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    public function index(TranslatorInterface $translator): Response
    {
//        dump([
//            'Debug data' => 'this package is awesome',
//            'Nested array' => [
//                [123],
//                'key' => 'value',
//                'user' => [
//                    'name' => 'Ilya',
//                    'age' => 18
//                ]
//            ],
//            'Current date-time' => date('Y-m-d H:i:s'),
//            'Class' => new JsonResponse([
//                'my json response'
//            ], Response::HTTP_OK)
//        ]);

        return $this->render('blog/main_page.html.twig', [
            'hello_translated' => $translator->trans('hello_translated'),
            'count' => 1
        ]);
    }

    /*
    public function routes()
    {
        $routes = [];

        foreach ($this->router->getRouteCollection()->all() as $route_name => $route) {
            $routes[$route_name] = $route->getPath();
        }

        return new JsonResponse($routes, Response::HTTP_OK);
    }*/
}
