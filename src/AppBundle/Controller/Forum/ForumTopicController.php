<?php 

namespace AppBundle\Controller\Forum;

use AppBundle\Entity\ForumTopic;
use AppBundle\Entity\ForumSubcategory;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ForumTopicController extends Controller {

    /**
     * @Route("/forum/topics/topic/new", name="forum_topic_new")
     */
    public function newTopicAction(Request $request) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $currentUsername = $this->getUser()->getUsername();
        $currentUserId = $this->getDoctrine()->getRepository("AppBundle:User")->findIdByUsername($currentUsername);

        $forumSubcategories = $this->getDoctrine()->getRepository("AppBundle:ForumSubcategory")->findAll();
        $forumTopic = new ForumTopic; 

        $form = $this->createFormBuilder($forumTopic)
                    ->add('title', TextType::class,
                        array(
                            'label' => 'Titlu',
                            'attr' =>
                            array(
                                'class' => 'form-control'
                            )
                        )
                    )
                    ->add('message', TextareaType::class,
                        array(
                            'label' => 'Mesaj',
                            'attr' =>
                            array(
                                'class' => 'form-control'
                            )
                        )
                    )
                    ->add('subcategory', ChoiceType::class,
                        array(
                            'label' => 'Alege subcategoria',
                            'choice_label' => 'name',
                            'choices' => $forumSubcategories
                        )
                    )
                    ->add("save", SubmitType::class,
                        array(
                            'label' => 'Scrie',
                            'attr' =>
                                array(
                                    'class' => 'btn btn-primary btn-lg'
                                )
                        )
                    )
                    ->getForm();

        $form->handleRequesT($request);

        if($form->isSubmitted() && $form->isValid()) {

            $title = $form['title']->getData();
            $message = $form['message']->getData();
            $subcategory = $form['subcategory']->getData();

            $userArray = $this->getDoctrine()->getRepository("AppBundle:User")->findByEmail($currentUsername);

            foreach($userArray as $user) {
                $dateAdded = new\DateTime('now');

                $forumTopic->setTitle($title);
                $forumTopic->setMessage($message);
                $forumTopic->setSubcategory($subcategory);
                $forumTopic->setUser($user);
                $forumTopic->setDateAdded($dateAdded);
    
                $entityManager = $this->getDoctrine()->getManager();
    
                $entityManager->persist($forumTopic);
                $entityManager->flush();
    
                $this->addFlash(
                    'notice',
                    'Topic adaguat cu success!'
                );
            }

            return $this->redirect($request->getUri());
        } 

        return $this->render("forum/topic-new.html.twig",array(
            'currentUsername' => $currentUsername,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/forum/topics/topic/user/topics", name="forum_topic_user_topics")
     */
    public function userTopicsAction() {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $currentUsername = $this->getUser()->getUsername();
        $currentUserId = $this->getDoctrine()->getRepository("AppBundle:User")->findIdByUsername($currentUsername);

        $forumTopics = $this->getDoctrine()->getRepository("AppBundle:ForumTopic")->findByUser($currentUserId);

        $forumSubcategories = $this->getDoctrine()->getRepository("AppBundle:ForumSubcategory")->findAll();

        foreach($forumTopics as $forumTopic) {

            $subcategoryId = $forumTopic->getSubcategory()->getId();

            $subcategoryName = "";

            foreach($forumSubcategories as $forumSubcategory) {
                if($forumSubcategory->getId() == $subcategoryId) {
                    $subcategoryName = $forumSubcategory->getName();
                    break;
                }
            }

            $forumTopic->setSubcategoryName($subcategoryName);

        }

        return $this->render("forum/topic-user-topics.html.twig", array(
            'currentUsername' => $currentUsername,
            "forumTopics" => $forumTopics
        ));
    }

    /**
     * @Route("/forum/topics/topic/edit/{topic_id}", name="forum_topic_edit")
     */
    public function editTopicAction($topic_id, Request $request) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $currentUsername = $this->getUser()->getUsername();
        $currentUserId = $this->getDoctrine()->getRepository("AppBundle:User")->findIdByUsername($currentUsername);

        $forumTopic = $this->getDoctrine()->getRepository("AppBundle:ForumTopic")->find($topic_id);
        $forumSubcategories = $this->getDoctrine()->getRepository("AppBundle:ForumSubcategory")->findAll();

        $form = $this->createFormBuilder($forumTopic)
                ->add('title', TextType::class,
                    array(
                        'label' => 'Titlu',
                        'attr' =>
                        array(
                            'class' => 'form-control'
                        )
                    )
                )
                ->add('message', TextareaType::class,
                    array(
                        'label' => 'Mesaj',
                        'attr' =>
                        array(
                            'class' => 'form-control'
                        )
                    )
                )
                ->add('subcategory', ChoiceType::class,
                    array(
                        'label' => 'Alege subcategoria',
                        'choice_label' => 'name',
                        'choices' => $forumSubcategories
                    )
                )
                ->add("save", SubmitType::class,
                    array(
                        'label' => 'Salveaza',
                        'attr' =>
                            array(
                                'class' => 'btn btn-success btn-lg'
                            )
                    )
                )
                ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $title = $form['title']->getData();
            $message = $form['message']->getData();
            $subcategory = $form['subcategory']->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $forumTopic = $entityManager->getRepository("AppBundle:ForumTopic")->find($topic_id);

            $forumTopic->setTitle($title);
            $forumTopic->setMessage($message);
            $forumTopic->setSubcategory($subcategory);

            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Modificarile au fost salvate!'
            );

            return $this->redirect($request->getUri());
        }
        

        return $this->render("forum/topic-edit.html.twig", array(
            'currentUsername' => $currentUsername,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/forum/topics/topic/delete/{topic_id}", name="forum_topic_delete")
     */
    public function deleteTopicAction($topic_id, Request $request) {

        $entityManager = $this->getDoctrine()->getManager();
        $forumTopic = $entityManager->getRepository("AppBundle:ForumTopic")->find($topic_id);

        if($forumTopic != null) {
            $entityManager->remove($forumTopic);
            $entityManager->flush();
        }

        $this->addFlash(
            'notice',
            'Topicul a fost sters cu success!'
        );

        return $this->redirectToRoute("forum_topic_user_topics");
    }
}