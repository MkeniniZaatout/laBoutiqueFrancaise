<?php 

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


Class Panier {

    private $session;
    private $entityManager;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function add($id ,$quantity = 1) {

        $panierActuel = $this->session->get('panier', []);

        // Je verifie si l'article n'est pas déjà dans le panier
        // pour faire de doublon
        if(!empty($panierActuel[$id])) {
            // Si s'est le cas j'incrémente la quantité
            $panierActuel[$id]['quantity'] += 1;
        } else {
            $produitComplet = [
                'product' => $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]), 
                'quantity' => 1
            ];
            $panierActuel[$id] = $produitComplet;
        }
        $this->session->set('panier', $panierActuel);
    }

    public function delete($id = "*") {
        if($id == "*") {
            $this->session->remove('panier');
        } else {
            $panier = $this->session->get('panier');
            unset($panier[$id]);
            $this->session->set('panier', $panier);
        }
    }



    public function get(string $element) {

        return $this->session->get($element);
    }
}

?>