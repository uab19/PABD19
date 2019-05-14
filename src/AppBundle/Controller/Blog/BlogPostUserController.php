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

class BlogPostUserController extends Controller {

    /**
     * @Route("/blog/post/my-posts", name="blog_user_posts")
     */
    public function userPostsAction(Request $request) {

        $session = $request->getSession();
        $currentUserId = $session->get('currentUserId');

        $blogPosts = $this->getDoctrine()->getRepository("AppBundle:BlogPost")->findDescByCreateDate($currentUserId);


        return $this->render("blog/blog-post-user.html.twig",[
            "blogPosts" => $blogPosts
        ]);
    }

    /**
     * @Route("/blog/post/edit/{postId}", name="blog_post_edit")
     */
    public function edtiPostAction($postId, Request $request) {

        $session = $request->getSession();
        $currentUserId = $session->get('currentUserId');
     
        $blogPost = $this->getDoctrine()->getRepository("AppBundle:BlogPost")->find($postId);

        $blogPostForm = $this->createFormBuilder($blogPost)
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
                                            'required' => false,
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

            $entityManager = $this->getDoctrine()->getManager();
            $blogPost = $entityManager->getRepository("AppBundle:BlogPost")->find($postId);

            if($file != null) {
                $filename = md5(uniqid()).'.'.$file->guessExtension();

                $file->move(
                    $blog_uploads_directory,
                    $filename
                );

                $blogPost->setImage($filename);
            }

            $blogPostTitle = $blogPostForm['title']->getData();
            $blogPostContent = $blogPostForm['content']->getData();
            $createDate = new \DateTime('now');

            $blogPost->setTitle($blogPostTitle);
            $blogPost->setContent($blogPostContent);
            $blogPost->setUserId($currentUserId);
            $blogPost->setCreateDate($createDate);
            
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Modificarile au fost salvate cu success!'
            );

            return $this->redirect($request->getUri());
        }

        return $this->render("blog/blog-post-edit.html.twig", [
            'blogPostForm' => $blogPostForm->createView()
        ]);
    }

    /**
     * @Route("/blog/post/delete/{postId}", name="blog_post_delete")
     */
    public function deletePostAction($postId) {

        $entityManager = $this->getDoctrine()->getManager();
        $blogPost = $entityManager->getRepository("AppBundle:BlogPost")->find($postId);

        $entityManager->remove($blogPost);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Postarea a fost stearsa cu success!'
        );

        return $this->redirectToRoute('blog_user_posts');
    }
}