<?php 

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

Class Panier {

    private $session;

    public function __construct(SessionInterface $session) {
        $this->session = $session;
    }

    public function add($id, $quantity) {

        $this->session->set('panier', [
            ['id' => $id, 'quantity' => $quantity]
        ]);
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