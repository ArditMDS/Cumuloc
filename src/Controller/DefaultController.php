<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(ProductsRepository $productsRepository): Response   
    {
        $clouds = $productsRepository->findAll();

        return $this->render("home.html.twig", [
            'clouds' => $clouds,
        ]);
    }
}
