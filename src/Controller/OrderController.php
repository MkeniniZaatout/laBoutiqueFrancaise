<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Classe\Panier;
use App\Entity\Livraison;
use App\Entity\LivraisonDetails;
use App\Form\OrderType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Classe\Mail;

class OrderController extends AbstractController
{


    private $entityManager;

    /**
     * AccountController
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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

    /**
     * @Route("/commande/recapitulatif", name="order_recap")
     */
    public function add(Panier $panier,Request $request): Response
    {

        $form = $this->createForm(OrderType::class, null,array('user' => $this->getUser())
        );

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData()['address'];
            $livreur = $form->getData()['livreurs'];
            $date = new \DateTime();

            // Enregistrer ma commande en base
            $livraison = new Livraison();
            $ref = $date->format('dmY') ."-". uniqid();
            $livraison->setRef($ref);
            $livraison->setUser($this->getUser());
            $livraison->setCreatedAt($date);
            $livraison->setPrice($livreur->getPrice());
            $livraison->setLivreurName($livreur->getName());
            // dd($address,$livreur,$livraison);
            $addressContent = $address->getFirstname() ." ". $address->getLastname() ."</br>". $address->getAddress()."</br>". $address->getPhone()."</br>".  $address->getPostal()."</br>".$address->getCity() ."</br>". $address->getCountry();
            if($address->getCompany() != null) {
                $addressContent.= "</br>".$address->getCompany();
            }
            if($address->getCodePorte() != null){
                $addressContent.= "</br>".$address->getCodePorte();
            }
            $livraison->setDeliveryAddress($addressContent);
            $livraison->setIsPaid(false);

            $this->entityManager->persist($livraison);
            
            // Enregistrer mes produits dans livraison details
            $prixTotal = null;
            foreach ($panier->get('panier') as $product) {
                $livraisonDetail = new LivraisonDetails();
                $livraisonDetail->setLivraison($livraison);
                $livraisonDetail->setProduct($product['product']->getName());
                $livraisonDetail->setQuantity($product['quantity']);
                $livraisonDetail->setPrice($product['product']->getPrice() / 100);
                $livraisonDetail->setTotal($livraisonDetail->getPrice() * $livraisonDetail->getQuantity());
                $this->entityManager->persist($livraisonDetail);
                $prixTotal += $livraisonDetail->getTotal();
            }

            $this->entityManager->flush();
        }
        return $this->render('order/add.html.twig', ['address' => $address, 'livreur' => $livreur, 'prixTotal' => $prixTotal, 'ref' => $livraison->getRef()]);
    }

    /**
     * @Route("/commande/success/{stripeSessionId}", name="order_validate")
    */
    public function succes(Panier $panier, $stripeSessionId): Response 
    {
        $livraison = $this->entityManager->getRepository(Livraison::class)->findOneBy(['stripeSessionId' => $stripeSessionId]);
        // Si la livraison n'existe pas ou que l'utilisateur tente d'accéder à une commande d'une autre personne
        if((!$livraison) || ($livraison->getUser() != $this->getUser()) ) {
            return $this->redirectToRoute('home', ['message' => "La livraison n'a pas été trouvé"]);
        }

        // Mettre le statut isPaid à true
        $livraison->setIsPaid(true);
        $this->entityManager->flush();
        // Envoyer un email pour confirmer l'achat de la commande.
        $notification = "Votre Commande s'est correctement déroulé. Vous pouvez des à présent suivre la livraison sur ce lien -> XXXXXXXX.";
        $search_email = $livraison->getuser()->getEmail();
        $search_email = $livraison->getFirstname();
        $mail = new Mail();
        $mail->send($livraison->getuser()->getEmail(), $livraison->getuser()->getFirstname(), 'Commande','<b>Bonjour Mr '.$livraison->getuser()->getFirstname().' '.$$livraison->getuser()->getLastname().'</b><br> '.$notification);
        // Vider le panier 
        $panier->delete(); 

        
        return $this->render('order/success.html.twig', ['livraison' => $livraison]);
    }


    /**
     * @Route("/commande/error/{stripeSessionId}", name="order_error")
     */
    public function error($stripeSessionId): Response 
    {
        $livraison = $this->entityManager->getRepository(Livraison::class)->findOneBy(['stripeSessionId' => $stripeSessionId]);
        // Si la livraison n'existe pas ou que l'utilisateur tente d'accéder à une commande d'une autre personne
        if((!$livraison) || ($livraison->getUser() != $this->getUser()) ) {
            return $this->redirectToRoute('home', ['message' => "La livraison n'a pas été trouvé"]);
        }
        
        // Mettre le statut isPaid à false
        $livraison->setIsPaid(false);
        $this->entityManager->flush();

        // Envoyer un email pour l'echec de l'achat de la commande.

        return $this->render('order/error.html.twig', ['livraison' => $livraison]);
    }
}
