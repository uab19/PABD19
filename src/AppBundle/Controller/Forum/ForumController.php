<?php 

namespace AppBundle\Controller\Forum;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends Controller {

    /**
     * @Route("/forum/home", name="forum_homepage")
     */
    public function forumHomeAction(Request $request) {

        return $this->render("forum/home.html.twig");
    }
}