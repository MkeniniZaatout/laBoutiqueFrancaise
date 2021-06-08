<?php

namespace App\Controller;

use App\Entity\Livraison;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    /**
     * @Route("/compte/order", name="account_order")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $orderIsPaid = $entityManager->getRepository(Livraison::class)->findBy(['isPaid' => 1]);
        // dd($orderIsPaid[0]->getLivraisonDetails()->getValues());

        return $this->render('account/order.html.twig', ['commandes' => $orderIsPaid]);
    }
}
