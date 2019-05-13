<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if ($request->getMethod() === "POST") {
            $username = $request->request->get('_username');
            $password = $request->request->get('_password');
            $confirmPassword = $request->request->get('_confirm_password');
            $validator = $this->get('validator');

            $user = new User();
            $user->setUsername($username);
            $user->setPassword($password);
            $user->setConfirmPassword($confirmPassword);

            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;

                return $this->render('@App/registration/registration.html.twig', [
                    'errors' => $errors
                ]);
            }

            $user->setPassword($encoder->encodePassword($user, $password));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect the user to login
            $this->addFlash('success', 'Successfully registered. Please log in with your new user account.');
            return $this->redirectToRoute('login');
        }

        return $this->render('@App/registration/registration.html.twig', [
            'errors' => []
        ]);
    }
}
