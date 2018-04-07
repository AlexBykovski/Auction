<?php

namespace App\Controller;

use App\Entity\Product;
use App\Parser\ProductParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/auction")
 */
class AuctionController extends BaseController
{
    static $defaultFilter = [
        "categories" => [],
        "times" => [],
    ];

    const COUNT_RECOMMEND_AUCTIONS = 3;

    /**
     * @Route("/show-last-finished", name="last_finished_auctions_show")
     */
    public function showLastFinishedAuctionsAction(Request $request)
    {
        $lastFinishedAuctions = $this->getProductRepository()->findLastEnded();

        return $this->render('client/auction/list-last-finished.html.twig', [
            "lastFinishedAuctions" => $lastFinishedAuctions,
        ]);
    }

    /**
     * @Route("/show-recommend", name="recommend_auctions_show")
     */
    public function showRecommendAuctionsAction(Request $request, ProductParser $productParser)
    {
        $myAuctions = $productParser->parserProducts($this->getProductRepository()->findCurrectAuctions(self::$defaultFilter, self::COUNT_RECOMMEND_AUCTIONS));

        return $this->render('client/auction/recommend.html.twig', [
            "recommendAuctions" => $myAuctions,
        ]);
    }

    /**
     * @Route("/details/{id}", name="show_auction_details")
     *
     * @ParamConverter("auction", class="App:Product", options={"id" = "id"})
     */
    public function showAuctionDetailsAction(Request $request, Product $auction)
    {
        return $this->render('client/auction/details.html.twig', [
            "auction" => $auction,
        ]);
    }
}