<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends Controller
{
    
    /**
     * @Route("/", name="pagina_pers")
     */
    public function pagPersAction(Request $request)
    {
      
        // replace this example code with whatever you need
        return $this->render('Marius/index.html.twig');
    }
    
    /**
     * @Route("/marius", name="pagina_stat")
     */
    public function pagStatAction(Request $request)
    {
        die('Hello World');
    }
}
