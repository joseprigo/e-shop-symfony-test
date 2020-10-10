<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;
use App\Repository\ProductRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/basket")
 */
class BasketController extends AbstractController{

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    function __construct(RouterInterface $router, ProductRepository $productRepository) {
        
        $this->router = $router;
        $this->productRepository = $productRepository;
    }

    
    /**
     * @Route("/add", name="basket_add")
     */
    public function add(Request $request){
        $id       = $request->request->get('product');
        $quantity = $request->request->get('quantity');
        $basket   = $this->get('session')->get('basket');
        $products = $this->get('session')->get('products');
        
        
        
        $product = $this->productRepository->find($id);
        

        if(!$products || !isset($products[$id])){
            $products[$id] = ['info' => $product, 'quantity' => $quantity];
        }else {
            $products[$id]['quantity'] += $quantity;
        }
        
        !isset($basket) ? $basket = $quantity : $basket += $quantity;
        
        $this->get('session')->set('products', $products);
        $this->get('session')->set('basket', $basket);
        
        return new RedirectResponse($this->router->generate('shop_index'));
    }
    
    /**
     * @Route("/add/{id}", name="basket_add_one")
     */
    public function addOne( $id){
        $basket   = $this->get('session')->get('basket');
        $products = $this->get('session')->get('products');
        
        $product = $this->productRepository->find($id);
        

        if(!$products || !isset($products[$id])){
            $products[$id] = ['info' => $product, 'quantity' => 1];
        }else {
            $products[$id]['quantity'] += 1;
        }
        
        !isset($basket) ? $basket = 1 : $basket ++;
        
        
        $this->get('session')->set('products', $products);
        $this->get('session')->set('basket', $basket);
        return new RedirectResponse($this->router->generate('shop_index'));
    }
    
    /**
     * @Route("/remove/{id}", name="basket_remove")
     */
    public function remove( $id){
        $basket   = $this->get('session')->get('basket');
        $products = $this->get('session')->get('products');
        $basket  -= $products[$id]['quantity'];
        unset($products[$id]);
        
        
        $this->get('session')->set('products', $products);
        $this->get('session')->set('basket', $basket);
        return new RedirectResponse($this->router->generate('shop_basket'));
    }
}
