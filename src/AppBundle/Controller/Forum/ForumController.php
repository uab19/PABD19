<?php 

namespace AppBundle\Controller\Forum;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class ForumController extends Controller {
    
    /**
     * @Route("/forum/home", name="forum_homepage")
     */
    public function forumHomeAction() {

        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        $currentUsername = $this->getUser()->getUsername();

        $categories = $this->getDoctrine()->getRepository("AppBundle:ForumCategory")->findAll();

        foreach($categories as $category) {
            $subcategories = $category->getSubcategory();

            foreach($subcategories as $subcategory) {

                $subcategory_id = $subcategory->getId();
                $entityManager = $this->getDoctrine()->getManager();

                $query = $entityManager->createQuery(
                    'SELECT COUNT(forumTopic.id)
                    FROM AppBundle:ForumTopic forumTopic
                    WHERE forumTopic.subcategory = :subcategory_id'
                )->setParameter('subcategory_id', $subcategory_id);

                $topicsNumber = $query->getSingleScalarResult();

                $queryReplies = $entityManager->createQuery(
                    'SELECT COUNT(forumReply.id) 
                    FROM AppBundle:ForumReply forumReply
                    WHERE forumReply.topic in 
                        (SELECT forumTopic.id from AppBundle:ForumTopic forumTopic WHERE forumTopic.subcategory = :subcategory_id) or
                        forumReply.reply in (select forumReply2.id from AppBundle:ForumReply forumReply2 where forumReply2.topic in 
                            (SELECT forumTopic2.id from AppBundle:ForumTopic forumTopic2 WHERE forumTopic2.subcategory = :subcategory_id))'
                )->setParameter('subcategory_id', $subcategory_id);

                $repliesNumber = $queryReplies->getSingleScalarResult();

                $subcategory->setTopicsNumber($topicsNumber);
                $subcategory->setRepliesNumber($repliesNumber);
            }
        }

        return $this->render("forum/home.html.twig",array(
            'currentUsername' => $currentUsername,
            'categories' => $categories
        ));
    }

    /**
     * @Route("/forum/topics/{subcategory_id}", name="forum_topics")
     */
    public function showTopicsAction($subcategory_id) {

        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        $currentUsername = $this->getUser()->getUsername();

        $topics = $this->getDoctrine()->getRepository("AppBundle:ForumTopic")->findBySubcategory($subcategory_id);

        $subcategory = $this->getDoctrine()->getRepository("AppBundle:ForumSubcategory")->find($subcategory_id);

        foreach($topics as $topic) {
            $topic_id = $topic->getId();
            $entityManager = $this->getDoctrine()->getManager();

            $queryRepliesNumber = $entityManager->createQuery(
                'SELECT COUNT(forumReply.id)
                FROM AppBundle:ForumReply forumReply
                WHERE forumReply.topic = :topic_id OR 
                (forumReply.reply IN (SELECT forumReplyB.id 
                FROM AppBundle:ForumReply forumReplyB 
                WHERE forumReplyB.topic = :topic_id))'
            )->setParameter('topic_id', $topic_id);
            
            $repliesNumber = $queryRepliesNumber->getSingleScalarResult();

            $topic->setRepliesNumber($repliesNumber);

            $user_id = $topic->getUser();
            $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($user_id);

            $authorName = $user->getUsername();
            $topic->setAuthorName($authorName);
        }

        return $this->render("forum/topics.html.twig", array(
            'currentUsername' => $currentUsername,
            'topics' => $topics,
            'subcategory' => $subcategory
        ));

    }
}