<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Classe\Panier;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session", name="stripe-create-session")
     */
    public function index(Panier $panier): Response
    {
        // Et ajouter dans un tableau pour stripe
        $product_for_stripe = array();
        $YOUR_DOMAIN = 'http://127.0.0.1:8000/';
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
        Stripe::setApiKey('sk_test_51IQaYSCmsFH1G20Fnol5x7kzI8C9SGYOxDTNwXTB8kFnIKCGXo2UwI3DCs5eEbQevZuzrr33F29vtOjh5dXPWA6i00z4f5ddkR');

        $checkout_session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [
            $product_for_stripe
        ],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/success.html',
        'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        $response = new JsonResponse(['id' => $checkout_session->id]);

        return $response;
    }
}
