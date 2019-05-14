<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class AlexPredaController extends Controller
{
    /**
     * @Route("/alex-preda/view", name="view_photo")
     */
    public function viewPhotoAction(Request $request)
    {
        $image = 'img1.jpg';

        return $this->render('Alex/detail.html.twig', [
            'image' => $image,
        ]);
    }
    
    
     /**
     * @Route("/alex-preda/gallery", name="pagina_galerie")
     */
    public function pagPhotoAction(Request $request)
    {
         $images = [
                'img1.jpg',
                'img7.jpg',
                'img3.jpg',
                'img4.jpg',
                'img3.jpg',
                'img7.jpg',
                'img7.jpg',
            ];
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $images,
            $request->query->getInt('different', 1) /*current page number*/,
            6 /*images per page*/
        );
        
        return $this->render('Alex/gallery.html.twig', [
           'images' => $pagination,
        ]);
    }
    
     /**
     * @Route("/alex-preda/contact", name="alex_preda_form_contact")
     */
    public function pagContactAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('nume', TextType::class)
            ->add('prenume', TextType::class)
            ->add('adresa', TextType::class)
            ->add('telefon', TextType::class)
            ->add('email', EmailType::class)
            ->add('message', TextareaType::class)
            ->add('send', SubmitType::class)
            ->getForm();   
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $form->getData();
            
            dump($data);
            
            $content = "<strong>Nume:</strong> " . $form->getData()['nume'] . "<br>"
                . "<strong>Prenume:</strong> " . $form->getData()['prenume'] . "<br>"
                . "<strong>Adresa:</strong> " . $form->getData()['adresa'] . "<br>"
                . "<strong>Telefon:</strong> " . $form->getData()['telefon'] . "<br>"
                . "<strong>Email:</strong> " . $form->getData()['email'] . "<br>"
                . "<strong>Mesaj:</strong> " . $form->getData()['message'] . "<br>";
            
            $message = \Swift_Message::newInstance()
                ->setSubject('Mesaj Contact Test')
                ->setFrom($form->getData()['email'])
                ->setTo($this->getParameter('mailer_to'))
                ->setBody($content);
            
            
            $this->get('mailer')->send($message);
            
            $this->addFlash('success', 'Formular trimis cu success');

            return $this->redirectToRoute('alex_preda_form_contact');
        }
        
         return $this->render('Alex/contact.html.twig', [
             'my_form' => $form->createView(),
         ]);
    }
    
    
}