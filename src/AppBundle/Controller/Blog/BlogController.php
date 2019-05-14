<?php 

namespace AppBundle\Controller\Blog;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends Controller {
    
    /**
     * @Route("/blog/home/", name="blog_home")
     */
    public function blogHomeAction() {
        
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        $currentUsername = $this->getUser()->getUsername();
        $currentUserId = $this->getDoctrine()->getRepository("AppBundle:User")->findIdByUsername($currentUsername);

        $blogPosts = $this->getDoctrine()->getRepository("AppBundle:BlogPost")->findAllDescByCreateDate();

        foreach($blogPosts as $blogPost) {
            $userId = $blogPost->getUserId();

            $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($userId);
            $userFullName = $user->getUsername();

            $blogPost->setUserFullName($userFullName);
        }
        
        $recentBlogPosts = $this->getDoctrine()->getRepository("AppBundle:BlogPost")->getRecentPosts();

        $archiveMonths = $this->getDoctrine()->getRepository("AppBundle:BlogPost")->getArchiveMonthsAndYears();

        return $this->render("blog/blog-home.html.twig", [
            'archiveMonths' => $archiveMonths,
            'recentBlogPosts' => $recentBlogPosts,
            'blogPosts' => $blogPosts
        ]);
    }
}