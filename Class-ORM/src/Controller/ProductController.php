<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;

class ProductController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {        
    }

    #[Route('/product', name: 'product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
    // Ahora debemos de definir las sentencias de CRUD para conectar con el Model (basicamente ahora mismo solo es la Entity pero también podran ser los Repositories que se conecten)
    
    // Create One
    #[Route('/product', name: 'create_one_product')]
    public function create(): Response
    {
        $product = new Product ();
        $product->setName('Nombre');
        $product->setPrice(100);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->redirectToRoute('product');
    }
    // Select All
    #[Route('/product', name: 'create_one_product')]
    public function getAllUsers(): Response
    {
        $users = $this->entityManager->getRepository(Product::class)->findAll();
    }

    // Select One

    // Delete One 

    // Update One



}
