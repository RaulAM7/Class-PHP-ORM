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

    #[Route('/product', name: 'product_index')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
    // Ahora debemos de definir las sentencias de CRUD para conectar con el Model (basicamente ahora mismo solo es la Entity pero también podran ser los Repositories que se conecten)
    
    // Create One
    #[Route('/product', name: 'product_create', methods: ['POST', 'GET'])]
    public function create(): Response
    {
        $product = new Product ();
        $product->setName('Nombre');
        $product->setPrice(100);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->redirectToRoute('product_index');
    }
    // Select All
    #[Route('/product', name: 'product_get_all')]
    public function getAllUsers(): Response
    {
        $products = $this->entityManager
            ->getRepository(Product::class)
            ->findAll();
        
        return $this->render('product/index.html.twig',
         ['products' => $products]
        );
    }   

    // Select One Product By ID
    #[Route('/product/{id}', name:'product_getByID', methods: ['POST'])]
    public function getOneByID($id)
    {
        $product = $this->entityManager
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) 
        {
            throw $this->createNotFoundException('Product with ID - ['.$id.'] - NOT FOUND');
        }

        return $this->render('product/fichaProducto.html.twig',
        ['product' => $product]);
    }
    // Update One
    #[Route('/product/update/{id}', name:'product_updateByID', methods: ['POST', 'PUT'])]

    public function updateByID($id): Response
    {
        $product = $this->entityManager
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product with ID - [' . $id . '] - NOT FOUND');
        }

        $product->setName('Nombre');
        $product->setPrice(100);
        

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->render('updateSucess.html.twig', ['product'=> $product]);

    }

    // Delete One

    




}
