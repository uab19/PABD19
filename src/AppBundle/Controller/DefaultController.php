<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $personalStatiRoutes = $this->getStaticRoutesWithImages();

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'routes' => $personalStatiRoutes
        ]);
    }

    /**
     * @Route("/pagini-personale", name="pagini-personale")
     */
    public function paginPersonaleAction(Request $request)
    {

        $personalStatiRoutes = $this->getStaticRoutesWithImages();

        return $this->render('index-pagini-personale/index.html.twig', [
            'routes' => $personalStatiRoutes
        ]);
    }

    private function getStaticRoutesWithImages() {
        $routes = [];

        $router = $this->container->get('router');
        /* @var $router \Symfony\Component\Routing\Router */

        $collection = $router->getRouteCollection();

        $allRoutes = $collection->all();

        foreach ($allRoutes as $key => $route)
        {
            if (isset($route->getDefaults()['img']) === true) {
                $staticRoute = [];
                $staticRoute['name'] = $key;
                $staticRoute['path'] = $route->getPath();
                $staticRoute['image'] = $route->getDefaults()['img'];

                $routes[] = $staticRoute;
            }
        }

        return $routes;
    }
    
}

