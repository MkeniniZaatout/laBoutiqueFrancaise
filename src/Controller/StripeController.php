<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Classe\Panier;
use App\Entity\Livraison;
use App\Entity\LivraisonDetails;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{

    /**
     * @Route("/commande/create-session/{reference}", name="stripe-create-session")
     */
    public function index(EntityManagerInterface $entityManagerInterface, Panier $panier, $reference): Response
    {
        // Et ajouter dans un tableau pour stripe
        $product_for_stripe = array();
        $YOUR_DOMAIN = 'http://127.0.0.1:8000/';
        $livraison = $entityManagerInterface->getRepository(Livraison::class)->findOneBy(['ref' => $reference]);
        
        if(!$livraison) {
            $response = new JsonResponse(['error' => 'Livraison not found']);
        }

        // $livraisonDetail = $livraison->getLivraisonDetails();
        // dd($livraisonDetail->getValues());

        foreach ($panier->get('panier') as $product) {
            $product_for_stripe[] = array(
                'quantity'=> $product['quantity'],
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => ($product['product']->getPrice()),
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'images' => [$YOUR_DOMAIN."uploads/".$product['product']->getIllustration()]
                        ]
                ]
            );
        }

        // Livreur à ajouter dans la transaction de stripe :
        $product_for_stripe[] = array(
            'quantity'=> 1,
            'description' => 'Livraison 🚚',
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => ($livraison->getPrice() * 100),
                
                'product_data' => [
                    'name' => $livraison->getLivreurName(),
                    'images' => [$YOUR_DOMAIN."img/livreur.jpg"]
                    ]
            ]
        );

        Stripe::setApiKey('sk_test_51IQaYSCmsFH1G20Fnol5x7kzI8C9SGYOxDTNwXTB8kFnIKCGXo2UwI3DCs5eEbQevZuzrr33F29vtOjh5dXPWA6i00z4f5ddkR');

        $checkout_session = Session::create([
        'payment_method_types' => ['card'],
        'customer_email' => $this->getUser()->getEmail(),
        // 'customer_name' => $this->getUser()->getFirstname()." ".$this->getUser()->getLastname(),
        'line_items' => [
            $product_for_stripe
        ],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . 'commande/success/{CHECKOUT_SESSION_ID}',
        'cancel_url' => $YOUR_DOMAIN . 'commande/error/{CHECKOUT_SESSION_ID}',
        ]);

        $livraison->setStripeSessionId($checkout_session->id);
        $entityManagerInterface->flush();

        $response = new JsonResponse(['id' => $checkout_session->id]);

        return $response;
    }
}
