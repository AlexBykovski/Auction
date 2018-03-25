<?php

namespace App\Controller;

use App\Entity\User;
use App\Parser\ProductParser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController extends BaseController
{
    /**
     * @Route("/get-update-products", name="get_update_products")
     */
    public function getUpdateProductsAction(Request $request, ProductParser $productParser)
    {
        $user = $this->getUser();
        $products = json_decode($request->getContent());

        if(!$products || !is_array($products) || !count($products)){
            $auctions = [];
        }
        else{
            $auctions = $productParser->parserProducts($this->getProductRepository()->findAuctionsByIds($products));
        }

        $parameters = [
            "success" => true,
            "auctions" => $auctions,
        ];

        if($user instanceof User){
            $parameters["user"] = $user->toArray();
        }

        return new JsonResponse($parameters);
    }
}