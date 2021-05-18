<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(): Response
    {
        $products = $this->getDoctrine()
        ->getRepository(Product::class)
        ->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/produit/{id}", name="product")
     */
    public function show($id): Response
    {
        $product = $this->getDoctrine()
        ->getRepository(Product::class)
        ->findOneBy(['id' => $id]);
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
