<?php

namespace App\Controller;

use App\Service\ProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:'/api/productos')]
class ApiController extends AbstractController
{
    private ProductsService $productsService;
    public function __construct(ProductsService $productsService)
    {
        $this->productsService = $productsService;
    }

    #[Route('/{id}', name: 'api-get-producto',  methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $product = $this->productsService->getProduct($id);
        if (!$product)
            return new JsonResponse("[]", JsonResponse::HTTP_NOT_FOUND);
        
        $data = $this->productsService->toArray($product);
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}
