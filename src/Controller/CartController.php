<?php

namespace App\Controller;
use App\Service\CartService;
use App\Service\ProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route(path:'/cart')]
class CartController extends AbstractController
{
    private $productsService;
    private $cart;
    public  function __construct(ProductsService $productsService, CartService $cart)
    {
        $this->productsService = $productsService;
        $this->cart = $cart;
    }

    #[Route('/', name: 'app_cart')]
    public function index(): Response
    {
        $items = $this->productsService->getCartProducts();
        $totalCart = $this->productsService->getCartTotal();

        return $this->render('cart/index.html.twig', ['items' => $items, 'totalCart' => $totalCart]);
    }

    #[Route('/{id}', name: 'cart_get', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function cart_get(Request $request, int $id): JsonResponse
    {
        $id = $id ?? -1; // El valor -1 no está en ningún producto, así que devolverá 404 si no se pasa un id válido
        
        $product = $this->productsService->getProduct($id);
        
        if (!$product)
            return new JsonResponse("[]", JsonResponse::HTTP_NOT_FOUND);

        $data = $this->productsService->toCartItem($product);
        $data["totalItems"] = $this->cart->totalItems();
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/add', name: 'cart_add', methods: ['POST'])]
    public function cart_add(Request $request): JsonResponse
    {
        //Cogemos el id del producto a añadir del cuerpo de la petición, si no se pasa un id válido devolverá 404
        $id = $request->request->getInt('id') ?? -1; // El valor -1 no está en ningún producto, así que devolverá 404 si no se pasa un id válido
        $product = $this->productsService->getProduct($id);
        if (!$product)
            return new JsonResponse("[]", JsonResponse::HTTP_NOT_FOUND);

        $this->cart->add($id, 1);

        $data = $this->productsService->toCartItem($product);
        $data["totalItems"] = $this->cart->totalItems();
        
        return $this->json($data, JsonResponse::HTTP_CREATED, [
                'Location' => $this->generateUrl('cart_get', ['id' => $id])
        ]);
    }

    #[Route('/update', name: 'cart_update', methods: ['PATCH'])]
    public function cart_update(Request $request): JsonResponse
    {
        //Cogemos el id del producto a añadir del cuerpo de la petición, si no se pasa un id válido devolverá 404
        $id = $request->request->getInt('id') ?? -1; // El valor -1 no está en ningún producto, así que devolverá 404 si no se pasa un id válido
        $quantity = $request->request->getInt('quantity') ?? 0  ; // El valor 1 es la cantidad por defecto    
        $product = $this->productsService->getProduct($id);
        if (!$product)
            return new JsonResponse("[]", JsonResponse::HTTP_NOT_FOUND);

        $this->cart->update($id, $quantity);

        $data = $this->productsService->toCartItem($product);
        $data["totalItems"] = $this->cart->totalItems();
        return new JsonResponse($data, JsonResponse::HTTP_OK);

    }

    #[Route('/delete', name: 'cart_delete', methods: ['DELETE'])]
    public function cart_delete(Request $request): JsonResponse
    {
        $id = $request->request->getInt('id') ?? -1;
        $this->cart->delete($id);
        $totalCart = $this->productsService->getCartTotal();
        $data = ['total'=> $totalCart, 'totalItems' => $this->cart->totalItems()];
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}
