<?php

namespace App\Controller;

use App\Entity\AutoStake;
use App\Entity\Product;
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

    /**
     * @Route("/get-update-single-product", name="get_update_single_product")
     */
    public function getUpdateProductAction(Request $request)
    {
        $user = $this->getUser();
        $productId = json_decode($request->getContent());

        if(!$productId){
            $auction = [];
        }
        else{
            /** @var Product $auctionObject */
            $auctionObject = $this->getProductRepository()->find($productId);
            $auction = $auctionObject->toArrayMainPage(true);

            $user = $this->getUser();

            if($user instanceof User){
                $autoStake = $this->getDoctrine()->getRepository(AutoStake::class)->findOneBy(["auction" => $auctionObject, "stakeDetail" => $user->getStakeDetail()]);

                $auction["hasAutoStake"] = $autoStake instanceof AutoStake;
                $auction["autoStakeStakes"] = $auction["hasAutoStake"] ? $autoStake->getCount() : null;
            }
        }

        $parameters = [
            "success" => true,
            "auction" => $auction,
        ];

        if($user instanceof User){
            $parameters["user"] = $user->toArray();
        }

        return new JsonResponse($parameters);
    }
}