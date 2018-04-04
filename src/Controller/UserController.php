<?php

namespace App\Controller;

use App\Entity\User;
use App\Parser\ProductParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    const DEFAULT_COUNT_MY_AUCTIONS = 50;

    /**
     * @Route("/my-auctions", name="profile_my_auctions")
     */
    public function myAuctionsShowAction(Request $request, ProductParser $productParser)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN") || !$this->isGranted("ROLE_USER")){
            return $this->redirectToRoute("list_products");
        }

        $myAuctions = $productParser->parserProducts($this->getProductRepository()
            ->findCurrentAuctionsByUser($this->getUser()));

        return $this->render('client/profile/my-auctions.html.twig', [
            "myAuctions" => $myAuctions
        ]);
    }
}