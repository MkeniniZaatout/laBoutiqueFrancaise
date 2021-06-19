<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Classe\Mail;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SessionInterface $session): Response
    {
        $mail = new Mail();
        $mail->send('marwa.mkenini.06@gmail.com','Marwa', 'test 1er mail', 'Test pour voir si sa fonctionne comme je veux');
        return $this->render('home/index.html.twig', []);
    }
}
