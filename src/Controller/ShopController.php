<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    
    /**
     * @Route("/show/{id}", name="shop_show")
     */
    public function show(Product $product){
        return $this->render('product/index.html.twig',
                [
                    'product' => $product
                ]);
    }
    
    /**
     * @Route("/basket/", name="shop_basket")
     */
    public function basket(){
        $basketProducts = $this->get('session')->get('products');
        $basketVouchers = $this->get('session')->get('vouchers');
        
        $finalTotal = 0; 
        foreach( $basketProducts as $product){
            if($product['info']->getName() == 'A' && array_key_exists('V', $basketVouchers)){
                $price = $product['info']->getPrice();
                $quantityWithDiscount = intdiv($product['quantity'], 2);
                $quantityNoDiscount = $product['quantity'] - $quantityWithDiscount;
                $finalTotal += $price * $quantityNoDiscount + $price * 0.9 * $quantityWithDiscount;
                
            }else if($product['info']->getName() == 'B' && array_key_exists('R', $basketVouchers)){
                $finalTotal += ($product['info']->getPrice() - 5) * $product['quantity'];
            }else{
                $finalTotal += $product['info']->getPrice() * $product['quantity'];
            }
            
        }
        
        if($finalTotal > 40 && array_key_exists('S', $basketVouchers)){
            $finalTotal = $finalTotal*0.95;
        }
        
        return $this->render('basket/index.html.twig',
                [
                    'products' => $basketProducts,
                    'vouchers' => $basketVouchers,
                    'total'    => $finalTotal
                ]);
    }
    
    /**
     * @Route("/voucher/", name="shop_voucher")
     */
    public function voucher(){
        
        return $this->render('voucher/index.html.twig');
    }
    
}
