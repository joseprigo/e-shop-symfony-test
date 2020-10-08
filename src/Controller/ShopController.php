<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shop")
 */
class ShopController extends AbstractController{

    private $productRepository;

    function __construct(ProductRepository $productRepository) {
        
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/", name="shop_index")
     */
    public function index()
    {
       
        
        return $this->render('shop/index.html.twig',
                [
                   'products' => $this->productRepository->findAll()
                ]);
    }
    
}
