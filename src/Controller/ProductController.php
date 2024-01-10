<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;

#[Route('/api', name: 'api_')]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_product', methods:['get'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $products = $doctrine
            ->getRepository(Product::class)
            ->findAll();
   
        $data = [];
   
        foreach ($products as $product) {
           $data[] = [
               'id' => $product->getId(),
               'name' => $product->getName(),
               'description' => $product->getDescription(),
           ];
        }
   
        return $this->json($data);
    }

    #[Route('/products', name: 'product_create', methods:['POST'])]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse

    {
        $entityManager = $doctrine->getManager();

        $newProduct = new Product();
        $newProduct->setName($request->request->get('name'));
        $newProduct->setDescription($request->request->get('description'));
        $newProduct->setImage($request->request->get('image'));

        $entityManager->persist($newProduct);
        $entityManager->flush();

        $products = $doctrine
            ->getRepository(Product::class)
            ->findAll();
   
        $data = [];
   
        foreach ($products as $product) {
           $data[] = [
               'id' => $product->getId(),
               'name' => $product->getName(),
               'description' => $product->getDescription(),
           ];
        }
   
        return $this->json($data);
    }

    #[Route('/products/{id}', name: 'product_show', methods:['get'])]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $products = $doctrine
            ->getRepository(Product::class)
            ->find($id);

        if (!$products){
            return $this->json('No product found for id' . $id, 404);
        }
   
        $data = [];
   
        foreach ($products as $product) {
           $data[] = [
               'id' => $product->getId(),
               'name' => $product->getName(),
               'description' => $product->getDescription(),
           ];
        }
   
        return $this->json($data);
    }


    
}
