<?php 

namespace AppBundle\Controller\Forum;

use AppBundle\Entity\ForumReply;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ForumTopicViewController extends Controller {

    /**
     * @Route("/forum/topics/topic/view/{topic_id}", name="forum_topic_view")
     */
    public function viewTopicAction($topic_id, Request $request) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $currentUsername = $this->getUser()->getUsername();
        $currentUserId = $this->getDoctrine()->getRepository("AppBundle:User")->findIdByUsername($currentUsername);

        $topic = $this->getDoctrine()->getRepository("AppBundle:ForumTopic")->find($topic_id);

        $topicAuthor = $this->getDoctrine()->getRepository("AppBundle:User")->find($topic->getUser());

        $replies = $this->getDoctrine()->getRepository("AppBundle:ForumReply")->findByTopic($topic_id);

        foreach($replies as $reply) {
            $userId = $reply->getUser();

            $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($userId);

            $reply->setAuthorName($user->getUsername());

            $replies2 = $reply->getReplies();

            foreach($replies2 as $reply2) {

                $userId2 = $reply2->getUser();

                $user2 = $this->getDoctrine()->getRepository("AppBundle:User")->find($userId2);

                $reply2->setAuthorName($user2->getUsername());
            }
        }

        $writeReplyForm = $this->buildWriteReplyForm();

        $writeReplyForm->handleRequest($request);

        if($writeReplyForm->isSubmitted() && $writeReplyForm->isValid()) {

            $forumReply = new ForumReply;

            $message = $writeReplyForm['message']->getData();
            $dateAdded = new\DateTime('now');

            $forumReply->setMessage($message);
            $forumReply->setDateAdded($dateAdded);
            
            $replyId = $writeReplyForm['reply_id']->getData();
            if($replyId != null) {

                $forumReply = $this->getDoctrine()->getRepository("AppBundle:ForumReply")->find($replyId);

                $forumReply->setReply($forumReply);
            } else {

                $forumReply->setTopic($topic);
            }

            $userArray = $this->getDoctrine()->getRepository("AppBundle:User")->findByEmail($currentUsername);

            foreach($userArray as $user) {
                $forumReply->setUser($user);
    
                $entityManager = $this->getDoctrine()->getManager();
    
                $entityManager->persist($forumReply);
                $entityManager->flush();
            }
            
            return $this->redirect($request->getUri());
        }

        $editReplyForm = $this->buildEditReplyForm();

        $editReplyForm->handleRequest($request);

        if($editReplyForm->isSubmitted() && $editReplyForm->isValid()) {

            $editMessage = $editReplyForm['edit_message']->getData();
            $editReplyId = $editReplyForm['edit_reply_id']->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $editForumReply = $entityManager->getRepository('AppBundle:ForumReply')->find($editReplyId);

            $editForumReply->setMessage($editMessage);

            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render("forum/topic-view.html.twig",array(
            'currentUsername' => $currentUsername,
            'topic' => $topic,
            'topicAuthor' => $topicAuthor,
            'replies' => $replies,
            'writeReplyForm' => $writeReplyForm->createView(),
            'editReplyForm' => $editReplyForm->createView()
        ));
    }

    private function buildWriteReplyForm() {
        
        $form = $this->createFormBuilder()
                ->add('message', TextareaType::class, 
                    array(
                        'label' => '',
                        'attr' => 
                            array(
                                'class' => 'form-control',
                                )
                            )
                    )
                ->add('reply_id', TextType::class,
                    array(
                        'label' => '',
                        'attr' => 
                            array(
                                'class' => 'hidden-input',
                            ),
                        'required' => false
                        )
                    )
                ->add('save', SubmitType::class, 
                    array(
                        'label' => 'Scrie raspuns',
                        'attr' => 
                            array(
                                'class' => 'btn btn-primary',
                                )
                            )
                    )
                ->getForm();

        return $form;

    }


    private function buildEditReplyForm() {
        $form = $this->createFormBuilder()
                ->add('edit_message', TextareaType::class, 
                    array(
                        'label' => '',
                        'attr' => 
                            array(
                                'class' => 'form-control',
                                )
                            )
                    )
                ->add('edit_reply_id', TextType::class,
                    array(
                        'label' => '',
                        'attr' => 
                            array(
                                'class' => 'hidden-input',
                            ),
                        'required' => false
                        )
                    )
                ->add('edit_save', SubmitType::class, 
                    array(
                        'label' => 'Salveaza modificarile',
                        'attr' => 
                            array(
                                'class' => 'btn btn-success',
                                )
                            )
                    )
                ->getForm();

        return $form;
    }

    /**
     * @Route("/forum/topics/topic/view/{topic_id}/reply/delete/{reply_id}", name="forum_reply_delete")
     */
    public function deleteReplyAction($topic_id, $reply_id) {

        $entityManager = $this->getDoctrine()->getManager();
        $forumReply = $entityManager->getRepository("AppBundle:ForumReply")->find($reply_id);

        if($forumReply != null) {
            $entityManager->remove($forumReply);
            $entityManager->flush();
        } 

        return $this->redirect("/forum/topics/topic/view/".$topic_id);
    }
}