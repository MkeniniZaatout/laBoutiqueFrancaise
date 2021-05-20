<?php 

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

Class Panier {

    private $session;

    public function __construct(SessionInterface $session) {
        $this->session = $session;
    }

    public function add($id ,$quantity) {

        // Je verifie si l'article n'est pas déjà dans le panier
        // pour faire de doublon
        $panier = $this->session->get('panier', []);
        if(!empty($panier[$id])) {
            // Si s'est le cas j'incrémente 
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $this->session->set('panier', $panier);
    }

    public function delete($id) {

        $this->session->remove('panier', [
            ['id' => $id]
        ]);
    }

    public function get(string $element) {

        return $this->session->get($element);
    }
}

?>