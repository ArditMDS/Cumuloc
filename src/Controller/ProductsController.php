<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index( ProductsRepository $productsRepository): Response
    {
        $result = $productsRepository->findAll();
        return $this->render('ourProducts/index.html.twig', [
            'ourProducts' => $result,
        ]);
    }
}
