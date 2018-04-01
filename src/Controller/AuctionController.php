<?php

namespace App\Controller;

use App\Parser\ProductParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuctionController extends BaseController
{
    const COUNT_RECOMMEND_AUCTIONS = 3;

    /**
     * @Route("/show-last-finished-auctions", name="last_finished_auctions_show")
     */
    public function showLastFinishedAuctionsAction(Request $request)
    {
        $lastFinishedAuctions = $this->getProductRepository()->findLastEnded();

        return $this->render('client/auction/list-last-finished.html.twig', [
            "lastFinishedAuctions" => $lastFinishedAuctions,
        ]);
    }

    /**
     * @Route("/show-recommend-auctions", name="recommend_auctions_show")
     */
    public function showRecommendAuctionsAction(Request $request, ProductParser $productParser)
    {
        $filterParams = [
            "categories" => [],
            "times" => [],
        ];

        $myAuctions = $productParser->parserProducts($this->getProductRepository()->findCurrectAuctions($filterParams, self::COUNT_RECOMMEND_AUCTIONS));

        return $this->render('client/auction/recommend.html.twig', [
            "myAuctions" => $myAuctions,
        ]);
    }
}