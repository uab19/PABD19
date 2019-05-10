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

            $authorName = $user->getFirstName()." ".$user->getLastName();
            $topic->setAuthorName($authorName);
        }

        return $this->render("forum/topics.html.twig", array(
            'topics' => $topics,
            'subcategory' => $subcategory
        ));

    }

    /**
     * @Route("/forum/topics/topic/view/{topic_id}", name="forum_topic_view")
     */
    public function viewTopicAction($topic_id) {

        $topic = $this->getDoctrine()->getRepository("AppBundle:ForumTopics")->find($topic_id);

        $user = $this->getDoctrine()->getRepository("AppBundle:Users")->find($topic->getUser());

        $replies = $this->getDoctrine()->getRepository("AppBundle:ForumReplies")->findByTopic($topic_id);

        foreach($replies as $r) {
            $this->log($r->getContent());

            $a = $r->getReplies();

            foreach($a as $b) {
                $this->log(">>>".$b->getContent());
            }
            
        }

        return $this->render("forum/topic-view.html.twig",array(
            'topic' => $topic,
            'user' => $user
        ));
    }

    private function log($text) {
        echo "<script>console.log(\"$text\");</script>";
    }
}