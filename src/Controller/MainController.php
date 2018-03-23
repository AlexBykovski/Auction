<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\MainPage;
use App\Parser\ProductParser;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends BaseController
{
    const AUCTION_PER_PAGE = 9;

    /**
     * @Route("/{page}", name="list_products", requirements={"page"="\d+"}, defaults={"page" = 1})
     */
    public function mainPageAction(Request $request, ProductParser $productParser, $page)
    {
        $page = !$page || $page < 1 ? 1 : $page;
        $offset = ($page - 1) * self::AUCTION_PER_PAGE;
        $count = $this->getProductRepository()->findCountNotFinishedAuctions();
        $leftCount = $count - $page * self::AUCTION_PER_PAGE;
        /** @var MainPage $mainPage */
        $mainPage = $this->getDoctrine()->getRepository(MainPage::class)->findAll()[0];

        $lastFinishedAuctions = $this->getProductRepository()->findLastEnded();
        $currentAuctions = $productParser->parserProducts($this->getProductRepository()->findCurrectAuctions(self::AUCTION_PER_PAGE, $offset));

        return $this->render('client/main.html.twig', [
            "sliderImages" => $mainPage->getSliderImages(),
            "soonProduct" => $mainPage->getSoonProduct(),
            "lastFinishedAuctions" => $lastFinishedAuctions,
            "currentAuctions" => $currentAuctions,
            "page" => $page,
            "leftCount" => $leftCount
        ]);
    }
}