<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/admin/products', name: 'app_admin')]
    public function index(Request $request, ProductsRepository $productsRepository): Response
    {
        $ourProducts = $productsRepository->findAll();

        return $this->render('admin/product/index.html.twig', [
            'ourProducts' => $ourProducts,
        ]);
    }

    #[Route('/admin/create', name: 'app_admin_create')]
    public function createProduct(Request $request): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnailName = $form->get('thumbnail')->getData();

            if ($thumbnailName && in_array($thumbnailName->guessExtension(), ['jpg', 'png', 'svg', 'pdf'])) {
                $originalFileName = pathinfo($thumbnailName->getClientOriginalName(), PATHINFO_FILENAME);

                $newFileName = md5(uniqid()) . '.' . $thumbnailName->guessExtension();
                try {
                    $thumbnailName->move(
                        $this->getParameter('uploads'),
                    $newFileName
                );
                } catch (FileException $e) {
                    echo $e;
                }

                $product->setThumbnail($newFileName);
                $this->em->persist($product);
                $this->em->flush();

                $form = $this->createForm(ProductType::class, new Products());
                return $this->redirectToRoute('app_admin');
            }
        }
        return $this->render('admin/product/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/edit/{id}', name: 'app_admin_edit')]
    public function editProduct(
            Request $request,
            int $id,
            ProductsRepository $productsRepository,
            ProductType $productType
        ): Response
    {
        $currentProduct = $productsRepository->findOneBy(['id' => $id]);
        $form = $this->createForm($productType::class, $currentProduct);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnailName = $form->get('thumbnail')->getData();

            if($thumbnailName !== null) {
                if ($thumbnailName && in_array($thumbnailName->guessExtension(), ['jpg', 'png', 'svg', 'pdf'])) {
                    $originalFileName = pathinfo($thumbnailName->getClientOriginalName(), PATHINFO_FILENAME);
    
                    $newFileName = md5(uniqid()) . '.' . $thumbnailName->guessExtension();
                    try {
                        $thumbnailName->move(
                            $this->getParameter('uploads'),
                        $newFileName
                    );
                    $currentProduct->setThumbnail($newFileName);
                    } catch (FileException $e) {
                        echo $e;
                    }
                } else {
                    $currentProduct->setThumbnail($thumbnailName);
                }
            }

            $this->em->persist($currentProduct);
            $this->em->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/remove/{id}', name: 'app_admin_remove')]
    public function removeProduct(int $id, ProductsRepository $productsRepository): Response
    {
        $currentProduct = $productsRepository->findOneBy(['id' => $id]);
       
            $this->em->remove($currentProduct);
            $this->em->flush();

            return $this->redirectToRoute('app_admin');

    }

}
