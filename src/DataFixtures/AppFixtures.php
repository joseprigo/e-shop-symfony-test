<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Voucher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
/**
 * run fixtures:
 * php bin/console doctrine:fixtures:load
 */
class AppFixtures extends Fixture
{
    private const PRODUCTS = [
        [
            'name' => 'A',
            'price' => 10,
        ],
        [
            'name' => 'B',
            'price' => 8,
        ],
        [
            'name' => 'C',
            'price' => 12,
        ],
    ];
    
    private const VOUCHERS = [
        [
            'name' => 'V',
            'description' => '10% off discount voucher for the second unit applying only to Product A',
        ],
        [
            'name' => 'R',
            'description' => '5€ off discount on product type B',
        ],
        [
            'name' => 'S',
            'description' => '5% discount on a cart value over 40€',
        ],
    ];
    
    public function load(ObjectManager $manager)
    {
        $this->loadProducts($manager);
        $this->loadVouchers($manager);
        
    }
    
    private function loadProducts(ObjectManager $manager){
        
        foreach(self::PRODUCTS as $productData){
            $product = new Product();
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $manager->persist($product);
        }
        $manager->flush();
    }
    
    private function loadVouchers(ObjectManager $manager){
        
        foreach(self::VOUCHERS as $voucherData){
            $voucher = new Voucher();
            $voucher->setName($voucherData['name']);
            $voucher->setDescription($voucherData['description']);
            $manager->persist($voucher);
        }
        $manager->flush();
    }
    
   
    
    
}
