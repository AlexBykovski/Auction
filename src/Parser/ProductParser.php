<?php

namespace App\Parser;

use App\Entity\Product;

class ProductParser
{
    public function parserProducts(array $products)
    {
        $parsedProducts = [];

        /** @var Product $product */
        foreach($products as $product){
            $parsedProducts[] = $product->toArrayMainPage();
        }

        return $parsedProducts;
    }
}