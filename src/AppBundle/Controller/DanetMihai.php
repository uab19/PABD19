<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DanetMihai extends Controller
{
    /**
    * @Route("/DanetMihai/number")
    */
    public function number()
    {
        

        return $this->render('DanetMihai.html.twig');
    }
}
