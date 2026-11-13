<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProductsService;

class ProductController extends AbstractController
{
    private ProductsService $productsService;
    public function __construct(ProductsService $productsService)
    {
        $this->productsService = $productsService;
    }
    #[Route('/product', name: 'product')]
    public function index(): Response
    {
        $products = $this->productsService->getProducts();
        return $this->render('product/product.html.twig', compact('products'));
    }
}
