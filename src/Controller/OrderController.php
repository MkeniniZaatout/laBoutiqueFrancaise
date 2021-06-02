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

class OrderController extends AbstractController
{
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
        // dd($panier->get('panier'));

        $form = $this->createForm(OrderType::class, null,array('user' => $this->getUser())
        );

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData()['address'];
            $livreur = $form->getData()['livreurs'];
            $date = new \DateTime();

            // Enregistrer ma commande en base
            $livraison = new Livraison();
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
            
            // dd($panier->get('panier')[0]->name);
            // Enregistrer mes produits dans livraison details
            foreach ($panier->get('panier') as $product) {
                $livraisonDetail = new LivraisonDetails();
                $livraisonDetail->setLivraison($livraison);
                $livraisonDetail->setProduct($product['product']->getName());
                $livraisonDetail->setQuantity($product['quantity']);
                $livraisonDetail->setPrice($product['product']->getPrice() / 100);
                $livraisonDetail->setTotal($livraisonDetail->getPrice() * $livraisonDetail->getQuantity());
                $this->entityManager->persist($livraisonDetail);
            }
            // $this->entityManager->flush();    
            // dd($livraison);
        }
        
        return $this->render('order/add.html.twig', ['address' => $address, 'livreur' => $livreur]);
    }
}
