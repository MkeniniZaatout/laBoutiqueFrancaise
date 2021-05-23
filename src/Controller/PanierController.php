<?php

namespace App\Controller;

use App\Classe\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/mon-panier", name="show_panier")
     */
    public function index(Panier $panier): Response
    {
        return $this->render('panier/index.html.twig', [
            'panier' => $panier->get('panier'),
        ]);
    }

    /**
     * @Route("/mon-panier/add/{id}", name="panier_add")
     */
    public function add($id, $quantity = 1, Panier $panier)
    {
        $panier->add($id, $quantity);
        return $this->redirectToRoute('show_panier'); 
    }

    /**
     * @Route("/mon-panier/remove/{id}", name="panier_remove_product")
     */
    public function removeProduct($id, Panier $panier)
    {
        $panier->delete($id);
        return $this->redirectToRoute('show_panier'); 
    }

    /**
     * @Route("/mon-panier/remove", name="panier_remove")
     */
    public function remove(Panier $panier)
    {
        $panier->delete();
        return $this->redirectToRoute('show_panier');
    }

    /**
     * @Route("/mon-panier/remove/quantity/{id}", name="panier_remove_quantity") 
     */
    public function removeQuantity($id, Panier $panier) 
    {
        $panier->decrease($id);
        return $this->redirectToRoute('show_panier');
    }
}
