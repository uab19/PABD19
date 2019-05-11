<?php 

namespace AppBundle\Controller\Forum;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\Session;

class ForumController extends Controller {
    
    /**
     * @Route("/forum/home", name="forum_homepage")
     */
    public function forumHomeAction(Request $request) {

        $session = $request->getSession();
        $session->set('currentUserId', 1);

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

        $subcategory = $this->getDoctrine()->getRepository("AppBundle:ForumSubcategories")->find($subcategory_id);

        foreach($topics as $topic) {
            $topic_id = $topic->getId();
            $entityManager = $this->getDoctrine()->getManager();

            $queryRepliesNumber = $entityManager->createQuery(
                'SELECT COUNT(forumReplies.id)
                FROM AppBundle:ForumReplies forumReplies
                WHERE forumReplies.topic = :topic_id OR 
                (forumReplies.reply IN (SELECT forumRepliesB.id 
                FROM AppBundle:ForumReplies forumRepliesB 
                WHERE forumRepliesB.topic = :topic_id))'
            )->setParameter('topic_id', $topic_id);
            
            $repliesNumber = $queryRepliesNumber->getSingleScalarResult();

            $topic->setRepliesNumber($repliesNumber);

            $user_id = $topic->getUser();
            $user = $this->getDoctrine()->getRepository("AppBundle:Users")->find($user_id);

            $authorName = $user->getLastName()." ".$user->getFirstName();
            $topic->setAuthorName($authorName);
        }

        return $this->render("forum/topics.html.twig", array(
            'topics' => $topics,
            'subcategory' => $subcategory
        ));

    }
}