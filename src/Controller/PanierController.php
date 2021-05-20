<?php

namespace App\Controller;

use App\Classe\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/mon-panier", name="panier")
     */
    public function index(Panier $panier): Response
    {
        dd($panier->get('panier'));
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    /**
     * @Route("/mon-panier/add/{id}", name="panier_add")
     */
    public function add($id, $quatity = 1, Panier $panier)
    {
        $panier->add($id, $quatity);
        return $this->redirectToRoute('panier'); 
    }

    /**
     * @Route("/mon-panier/remove/{id}", name="panier_remove")
     */
    public function remove($id, Panier $panier)
    {
        $panier->delete($id);
        return $this->redirectToRoute('panier'); 
    }

}
