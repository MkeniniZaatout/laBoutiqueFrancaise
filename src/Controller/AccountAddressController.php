<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
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
     * @Route("/compte/address", name="compte_address")
     */
    public function index(): Response
    {
        $this->getUser()->getUserAddress();
        return $this->render('account/address.html.twig', [
            'addresses' => $this->getUser()->getUserAddress()
        ]);
    }

    /**
     * @Route("/compte/address/add", name="compte_address_add")
     */
    public function add(Request $request): Response
    {
        $address = new Address();

        $form = $this->createForm(AddressType::class, $address);
        
        $form->handleRequest($request);
        if($form->isSubmitted() &&  $form->isValid() ) {
            $address->setUser($this->getUser());            
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            return $this->redirectToRoute('compte_address');
        }
        
        return $this->render('account/add_address.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/address/remove/{id}", name="compte_address_remove")
     */
    public function remove($id)
    {
        $address = new Address();

        return $this->redirectToRoute('compte_address');
    }

    /**
     * @Route("/compte/address/edit/{id}", name="compte_address_edit")
     */
    public function edit($id)
    {
        $address = new Address();

        return $this->redirectToRoute('compte_address');
    }
}
