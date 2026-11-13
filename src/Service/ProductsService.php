<?php
namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\CartService;

class ProductsService{
    private $repository;
    private $cart;

    public function __construct(ProductRepository $repository, CartService $cart)
    {
        $this->repository = $repository;
        $this->cart = $cart;
    }
    public function getProducts(): array{
        return $this->repository->findAll();
    }
    public function getProduct(int $id): ?Product{
        return $this->repository->find($id);
    }
    public function getCartProducts(): array{
        $products = $this->repository->getFromCart($this->cart);
        $data = [];

        foreach($products as $product){
            $data[] = $this->toCartItem($product);
        }

        return $data;
    }
    public function toArray(Product $product): array{
        return [
            "id"=> $product->getId(),
            "name" => $product->getName(),
            "price" => $product->getPrice(),
            "photo" => $product->getPhoto()
        ];
    }
    public function toCartItem(Product $product): array{
        $item = $this->toArray($product);
        $item["quantity"] = $this->cart->getCart()[$product->getId()] ?? 0;
        return $item;
    }   
    public function getCartTotal(): float{
        $total = 0;
        foreach($this->getCartProducts() as $item){
            $total += $item["quantity"] * $item["price"];
        }
        return $total;
    }
}
