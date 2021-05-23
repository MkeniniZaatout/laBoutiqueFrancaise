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

    public function add($id) {

        $panierActuel = $this->session->get('panier', []);

        // Je verifie si l'article n'est pas déjà dans le panier
        // pour faire de doublon
        if(!empty($panierActuel[$id])) {
            // Si s'est le cas j'incrémente la quantité
            $panierActuel[$id]['quantity'] += 1;
        } else {
            $product_object = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
            (!$product_object) ? die('Action impossible, produit inexistant') : $panierActuel[$id] = ['product' => $product_object, 'quantity' => 1];
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

    public function decrease($id) {
        $panier = $this->get('panier');
        if(isset($panier[$id])) {
            $panier[$id]['quantity'] > 1 ? $panier[$id]['quantity'] -= 1 : $this->delete($id);
            $this->session->set('panier', $panier);
        } else {
            die('Action impossible');
        }
    }
}

?>