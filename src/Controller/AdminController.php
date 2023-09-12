<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductType;
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

    #[Route('/admin', name: 'app_admin')]
    public function index(Request $request): Response
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

                $this->em->persist($product);
                $this->em->flush();

                $form = $this->createForm(ProductType::class, new Products());
                $this->redirectToRoute('app_admin');
            }
        }
        return $this->render('admin/index.html.twig', [
            'form' => $form,
        ]);
    }
}
