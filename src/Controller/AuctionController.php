<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuctionController extends BaseController
{
    /**
     * @Route("/show-last-finished-auctions", name="last_finished_products_show")
     */
    public function showLastFinishedAuctionsAction(Request $request)
    {
        $lastFinishedAuctions = $this->getProductRepository()->findLastEnded();

        return $this->render('client/auction/list-last-finished.html.twig', [
            "lastFinishedAuctions" => $lastFinishedAuctions,
        ]);
    }
}