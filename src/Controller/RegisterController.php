<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Classe\Mail;

class RegisterController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // $search_email = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            
            $search_email = $user->getEmail();
            if(isset($search_email)) {
                //Encodage du mdp
                $password = $user->getPassword();
                $user->setPassword($encoder->encodePassword($user,$password));
                /*
                $doctrine = $this->getDoctrine()->getManager();
                $doctrine->persist($user);
                $doctrine->flush();
                */
                // Envoi du mail d'inscription : 
                $notification = "Votre inscription s'est correctement déroulé.Vous pouvez des à présent accéder à votre compte.";
                $mail = new Mail();
                $mail->send($search_email, $user->getFirstname(), 'Inscription','<b>Bonjour Mr '.$user->getFirstname().' '.$user->getLastname().'</b><br> '.$notification);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
            } else {
                $notification = 'Erreur';
            }

        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(), 'notification' => $notification
        ]);
    }
}
