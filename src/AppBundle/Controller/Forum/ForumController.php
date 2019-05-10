<?php 

namespace AppBundle\Controller\Forum;

use AppBundle\Entity\ForumCategories;
use AppBundle\Entity\ForumSubcategories;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends Controller {

    /**
     * @Route("/forum/home", name="forum_homepage")
     */
    public function forumHomeAction() {

        $categories = $this->getDoctrine()->getRepository("AppBundle:ForumCategories")->findAll();

        foreach($categories as $category) {
            $subcategories = $category->getSubcategories();

            foreach($subcategories as $subcategory) {

                $subcategory_id = $subcategory->getId();
                $entityManager = $this->getDoctrine()->getManager();

                $query = $entityManager->createQuery(
                    'SELECT COUNT(forumTopics.id)
                    FROM AppBundle:ForumTopics forumTopics
                    WHERE forumTopics.subcategory = :subcategory_id'
                )->setParameter('subcategory_id', $subcategory_id);

                $topicsNumber = $query->getSingleScalarResult();

                $subcategory->setTopicsNumber($topicsNumber);
            }
        }

        return $this->render("forum/home.html.twig",array(
            'categories' => $categories
        ));
    }

    /**
     * @Route("/forum/topics/{subcategory_id}", name="forum_topics")
     */
    public function showTopicsAction($subcategory_id) {

        $topics = $this->getDoctrine()->getRepository("AppBundle:ForumTopics")->findBySubcategory($subcategory_id);

        foreach($topics as $t) {
            $aux = $t.getTitle();
            $this->log($aux);
        }

        return $this->render("forum/topics.html.twig");

    }

    private function log($text) {
        echo "<script>console.log(\"$text\");</script>";
    }
}