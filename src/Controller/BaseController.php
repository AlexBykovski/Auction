<?php

namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * @return ProductRepository
     */
    public function getProductRepository()
    {
        return $this->getDoctrine()->getRepository(Product::class);
    }
}