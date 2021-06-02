<?php

namespace App\Controller;

use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{
    /**
     * @Route("/commande", name="order")
     */
    public function index(Request $request): Response
    {
        if(!$this->getUser()->getUserAddress()->getValues()) {
            return $this->redirectToRoute('compte_address_add', ['Information' => 'Ajouter au moins une adresse de livraison pour valider votre commande']);
        }

        $form = $this->createForm(OrderType::class, null,array('user' => $this->getUser())
        );

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData()['address'];
            $livreur = $form->getData()['livreurs'];
        } 
        
        return $this->render('order/index.html.twig', [
            'formOrder' => $form->createView()
        ]);
    }
}
