<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;
use App\Repository\VoucherRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/voucher")
 */
class VoucherController extends AbstractController{

    /**
     * @var VoucherRepository
     */
    private $voucherRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    function __construct(RouterInterface $router, VoucherRepository $voucherRepository) {
        
        $this->router = $router;
        $this->voucherRepository = $voucherRepository;
    }

    
    /**
     * @Route("/add", name="voucher_add")
     */
    public function add(Request $request){
        $name         = strtoupper($request->request->get('voucher'));
        $usedVouchers = $this->get('session')->get('vouchers');
        $usedVouchers !== null ?: $usedVouchers = [];
        
        
        $voucher = $this->voucherRepository->findBy(['name' => $name])[0];
        
        if($voucher && !in_array($voucher->getName(),$usedVouchers)){
            $usedVouchers[$name] = $voucher;
            $this->get('session')->set('vouchers',$usedVouchers);
            return $this->render('voucher/success.html.twig');
        }else{
            return $this->render('voucher/fail.html.twig');
        }
        
        
    }
    
    

}
