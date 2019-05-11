<?php 

namespace AppBundle\Controller\Forum;

use AppBundle\Entity\ForumReplies;

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

        $session = $request->getSession();
        $currentUserId = $session->get('currentUserId');

        $topic = $this->getDoctrine()->getRepository("AppBundle:ForumTopics")->find($topic_id);

        $topicAuthor = $this->getDoctrine()->getRepository("AppBundle:Users")->find($topic->getUser());

        $replies = $this->getDoctrine()->getRepository("AppBundle:ForumReplies")->findByTopic($topic_id);

        foreach($replies as $reply) {
            $userId = $reply->getUser();

            $user = $this->getDoctrine()->getRepository("AppBundle:Users")->find($userId);

            $reply->setAuthorName($user->getLastName()." ".$user->getFirstName());

            $replies2 = $reply->getReplies();

            foreach($replies2 as $reply2) {

                $userId2 = $reply2->getUser();

                $user2 = $this->getDoctrine()->getRepository("AppBundle:Users")->find($userId2);

                $reply2->setAuthorName($user2->getLastName()." ".$user2->getFirstName());
            }
        }

        $writeReplyForm = $this->buildWriteReplyForm();

        $writeReplyForm->handleRequest($request);

        if($writeReplyForm->isSubmitted() && $writeReplyForm->isValid()) {

            $forumReplies = new ForumReplies;

            $message = $writeReplyForm['message']->getData();
            $dateAdded = new\DateTime('now');

            $forumReplies->setMessage($message);
            $forumReplies->setDateAdded($dateAdded);
            
            $replyId = $writeReplyForm['reply_id']->getData();
            if($replyId != null) {
                $forumReply = $this->getDoctrine()->getRepository("AppBundle:ForumReplies")->find($replyId);

                $forumReplies->setReply($forumReply);
            } else {
                $forumReplies->setTopic($topic);
            }

            $currentUser = $this->getDoctrine()->getRepository("AppBundle:Users")->find($currentUserId);

            $forumReplies->setUser($currentUser);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($forumReplies);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        $editReplyForm = $this->buildEditReplyForm();

        $editReplyForm->handleRequest($request);

        if($editReplyForm->isSubmitted() && $editReplyForm->isValid()) {

            $editMessage = $editReplyForm['edit_message']->getData();
            $editReplyId = $editReplyForm['edit_reply_id']->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $editForumReply = $entityManager->getRepository('AppBundle:ForumReplies')->find($editReplyId);

            $editForumReply->setMessage("EDITAT: ".$editMessage);

            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render("forum/topic-view.html.twig",array(
            'topic' => $topic,
            'topicAuthor' => $topicAuthor,
            'replies' => $replies,
            'writeReplyForm' => $writeReplyForm->createView(),
            'currentUserId' => $currentUserId,
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
        $forumReplies = $entityManager->getRepository("AppBundle:ForumReplies")->find($reply_id);

        if($forumReplies != null) {
            $entityManager->remove($forumReplies);
            $entityManager->flush();
        } 

        return $this->redirect("/forum/topics/topic/view/".$topic_id);
    }
}