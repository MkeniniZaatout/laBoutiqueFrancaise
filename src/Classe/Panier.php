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
            // Si s'est le cas j'incrémente 
            $panierActuel[$id]['quantity'] += 1;
        } else {
            $produitComplet = [];
            $produitComplet[$id] = [
                'product' => $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]), 
                'quantity' => $quantity
            ];
            array_push($panierActuel, $produitComplet[$id]);
        }
        $this->session->set('panier', $panierActuel);
        dd($this->get('panier'));
    }

    public function delete($id) {

        $this->session->remove('panier', [
            ['id' => $id]
        ]);
    }

    public function findProduct($idProduct) {

    }

    public function get(string $element) {

        return $this->session->get($element);
    }
}

?>