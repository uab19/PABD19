<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
     /**
     * @Route("/contact", name="form_contact")
     */
    public function pagContactAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->add('message', TextareaType::class)
            ->add('send', SubmitType::class)
            ->getForm();   
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $form->getData();
            
            dump($data);
            
            $content = "<strong>Email:</strong> " . $form->getData()['email'] . "<br>"
                . "<strong>Message:</strong> " . $form->getData()['message'] . "<br>";
            
            $message = \Swift_Message::newInstance()
                ->setSubject('Mesaj contact nou')
                ->setFrom($form->getData()['email'])
                ->setTo($this->getParameter('mailer_to'))
                ->setBody($content);
            
            $this->get('mailer')->send($message);
        }
        
         return $this->render('IgaSanda/index.html.twig', [
             'my_form' => $form->createView(),
         ]);
    }
    
    /**
     * @Route("/", name="pagina_personala")
     */
    public function pagPersonalaAction(Request $request)
    {
      
        // replace this example code with whatever you need
        return $this->render('IgaSanda/index.html.twig');
    }
    
    /**
     * @Route("/IgaSanda", name="pagina_statica")
     */
    public function pagStaticAction(Request $request)
    {
        die('Salutare! Sunt Iga Sanda');
    }
}