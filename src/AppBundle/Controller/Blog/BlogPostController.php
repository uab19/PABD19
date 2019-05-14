<?php 

namespace AppBundle\Controller\Blog;

use AppBundle\Entity\BlogPost;
use AppBundle\Entity\BlogPostComment;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BlogPostController extends Controller {
    
    /**
     * @Route("/blog/post/view/{postId}", name="blog_post_view")
     */
    public function blogPostViewAction($postId, Request $request) {

        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        $currentUsername = $this->getUser()->getUsername();

        $blogPost = $this->getDoctrine()->getRepository("AppBundle:BlogPost")->find($postId);

        $userId = $blogPost->getUserId();

        $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($userId);
        $userFullName = $user->getUsername();

        $blogPost->setUserFullName($userFullName);

        $blogPostComments = $this->getDoctrine()->getRepository("AppBundle:BlogPostComment")->findByBlogPost($blogPost);

        foreach($blogPostComments as $blogPostComment) {
            
            $commentId = $blogPostComment->getUser();

            $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($commentId);
            $userFullName = $user->getUsername();

            $blogPostComment->setUserFullName($userFullName);
        }

        $blogPostComment = new BlogPostComment();
        $blogPostCommentForm = $this->createFormBuilder($blogPostComment)
                                    ->add('message', TextareaType::class, 
                                        [
                                            'label' => false,
                                            'attr' =>
                                                [
                                                    'class' => 'form-control'
                                                ]
                                        ]
                                    )
                                    ->add('writecomment', SubmitType::class,
                                        [
                                            'label' => 'Adauga comentariu',
                                            'attr' =>
                                                [
                                                    'class' => 'btn btn-primary'
                                                ]   
                                        ]
                                    )
                                    ->getForm();

        $blogPostCommentForm->handleRequest($request);

        if($blogPostCommentForm->isSubmitted() && $blogPostCommentForm->isValid()) {
            $message = $blogPostCommentForm['message']->getData();
            $createDate = new \DateTime('now');

            $currentUser = $this->getDoctrine()->getRepository("AppBundle:User")->findByEmail($currentUsername);

            foreach($currentUser as $user) {

                $blogPostComment->setMessage($message);
                $blogPostComment->setUser($user);
                $blogPostComment->setBlogPost($this->getDoctrine()->getRepository("AppBundle:BlogPost")->find($postId));
                $blogPostComment->setCreateDate($createDate);
    
                $entityManager = $this->getDoctrine()->getManager();
    
                $entityManager->persist($blogPostComment);
                $entityManager->flush();
            } 

            return $this->redirect($request->getUri());
        };

        return $this->render("blog/blog-post-view.html.twig", [
            'currentUsername' => $currentUsername,
            'blogPost' => $blogPost,
            'blogPostComments' => $blogPostComments,
            'blogPostCommentForm' => $blogPostCommentForm->createView()
        ]);
    }

    /**
     * @Route("/blog/post/new", name="blog_post_new")
     */
    public function blogPostNewAction(Request $request) {

        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        $currentUsername = $this->getUser()->getUsername();
        $currentUserId = $this->getDoctrine()->getRepository("AppBundle:User")->findIdByUsername($currentUsername);

        $blogPostForm = $this->createFormBuilder()
                                ->add('title', TextType::class, 
                                        [
                                            'label' => 'Titlu',
                                            'attr' =>
                                                [
                                                    'class' => 'form-control'
                                                ]
                                        ]
                                )
                                ->add('image', FileType::class,
                                        [
                                            'mapped' => false,
                                            'label' => "Imagine",
                                            'attr' =>
                                                [
                                                    'accept' => "image/*",
                                                    'class' => 'btn btn-file'
                                                ]
                                        ]
                                )
                                ->add('content', TextareaType::class, 
                                    [
                                        'label' => false,
                                        'attr' =>
                                            [
                                                'rows' => '50',
                                                'class' => 'form-control'
                                            ]
                                    ]
                                )
                                ->add('writepost', SubmitType::class,
                                    [
                                        'label' => 'Adauga postarea',
                                        'attr' =>
                                            [
                                                'class' => 'btn btn-primary'
                                            ]   
                                    ]
                                )
                                ->getForm();


        $blogPostForm->handleRequest($request);

        if($blogPostForm->isSubmitted() && $blogPostForm->isValid()) {
            
            $blog_uploads_directory = $this->getParameter('blog_uploads_directory');

            $file = $blogPostForm->get('image')->getData();

            $filename = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $blog_uploads_directory,
                $filename
            );

            $blogPostTitle = $blogPostForm['title']->getData();
            $blogPostContent = $blogPostForm['content']->getData();
            $createDate = new \DateTime('now');

            $blogPost = new BlogPost();

            $blogPost->setTitle($blogPostTitle);
            $blogPost->setContent($blogPostContent);
            $blogPost->setUserId($currentUserId);
            $blogPost->setCreateDate($createDate);
            $blogPost->setImage($filename);
            
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($blogPost);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Post adaugat!'
            );

            return $this->redirectToRoute('blog_post_new');
        }

        return $this->render("blog/blog-post-new.html.twig", [
            'currentUsername' => $currentUsername,
            'blogPostForm' => $blogPostForm->createView()
        ]);
    }

    /**
     * @Route("/blog/post/view/{postId}/comment/delete/{commentId}", name="blog_comment_delete")
     */
    public function deleteCommentAction($postId, $commentId) {

        $entityManager = $this->getDoctrine()->getManager();
        $blogPostComment = $entityManager->getRepository("AppBundle:BlogPostComment")->find($commentId);

        $entityManager->remove($blogPostComment);
        $entityManager->flush();

        return $this->redirect("/blog/post/view/".$postId);
    } 
}