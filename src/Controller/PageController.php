<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProductsService;
use App\Service\TeamService;

class PageController extends AbstractController
{
    private $productsService;
    private $teamService;
    public function __construct(ProductsService $productsService, TeamService $teamService)
    {
        $this->productsService = $productsService;
        $this->teamService = $teamService;
    }
    public function teamTemplate(): Response
    {
        $team = $this->teamService->getTeam();
        return $this->render('partials/_team.html.twig', 
            compact('team')
        );
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $products = $this->productsService->getProducts();
        return $this->render('page/index.html.twig', compact('products'));
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('page/about.html.twig');
    }

    #[Route('/service', name: 'service')]
    public function service(): Response
    {
        return $this->render('page/service.html.twig', []);
    }

    #[Route('/price', name: 'price')]
    public function price(): Response
    {
        return $this->render('page/price.html.twig', []);
    }

    #[Route('/team', name: 'team')]
    public function team(): Response
    {
        return $this->render('page/team.html.twig', []);
    }

    #[Route('/testimonial', name: 'testimonial')]
    public function testimonial(): Response
    {
        return $this->render('page/testimonial.html.twig', []);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', []);
    }

}
