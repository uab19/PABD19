<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Gallery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends Controller
{
    /**
     * @Route("/gallery/", name="app_gallery_index")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $upload_form = $this->getUploadForm(new Gallery());

        $gallery = $this->getDoctrine()
            ->getRepository(Gallery::class)
            ->findBy([], ['id' => 'DESC']);

        dump($gallery);

        return $this->render('gallery/index.html.twig', [
            'upload_form' => $upload_form->createView(),
            'gallery' => $gallery,
        ]);
    }

    /**
     * @Route("/gallery/upload/", name="app_gallery_upload")
     */
    public function uploadAction(Request $request)
    {
        $gallery = new Gallery();
        $upload_form = $this->getUploadForm($gallery);

        $upload_form->handleRequest($request);

        if ($upload_form->isSubmitted() && $upload_form->isValid()) {
            $web_path = $this->getParameter('kernel.project_dir') . DIRECTORY_SEPARATOR . 'web';
            $upload_path = 'uploads'.DIRECTORY_SEPARATOR.'gallery';

            $em = $this->getDoctrine()->getManager();

            $file = $gallery->getFile();
            $file_name = md5(time()).rand(1, 100).'.'.$file->guessExtension();

            $file->move($web_path.DIRECTORY_SEPARATOR.$upload_path, $file_name);

            $gallery->setFile($upload_path.DIRECTORY_SEPARATOR.$file_name);

            $em->persist($gallery);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('app_gallery_index'));
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getUploadForm(Gallery $gallery)
    {
        return $this->createFormBuilder($gallery, ['action' => '/gallery/upload/'])
            ->add('title')
            ->add('file')
            ->getForm();
    }

}
