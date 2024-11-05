<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;

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
    #[Route('/product/create', name: 'product_create', methods: ['POST', 'GET'])]
    public function create(): Response
    {
            $product = new Product ();
            $product->setName('Nombre');
            $product->setPrice(100);
    
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $arrResult = $product->toArray();

            return $this->render('product/createSucess.html.twig', 
            ['product' => $arrResult]);
    }
    
    // Select All
    #[Route('/product', name: 'product_select_all')]
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
    #[Route('/product/{id}', name:'product_select_byID', methods: ['GET'])]
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
    #[Route('/product/update/{id}', name: 'product_update_byID', methods: ['POST', 'PUT'])]
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
    #[Route('product/delete/{id}', name:'product_delete_byID', methods: ['POST'])]
    public function deleteByID($id): Response
    {
        $product = $this->entityManager
        ->getRepository(Product::class)
        ->find($id);

        if (!$product)
        {
            throw $this->createNotFoundException('Product with ID - ['.$id.'] - NOT FOUND');
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return $this->render('deleteSucess.html.twig');
    }
}
