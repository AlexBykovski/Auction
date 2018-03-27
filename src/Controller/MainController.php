<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\MainPage;
use App\Entity\Product;
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
        $query = parse_url($request->getRequestUri(), PHP_URL_QUERY);
        $filterParams = $this->parseQueryParams($request->query->all());
        $page = !$page || $page < 1 ? 1 : $page;
        $offset = ($page - 1) * self::AUCTION_PER_PAGE;
        $count = $this->getProductRepository()->findCountNotFinishedAuctions($filterParams);
        $leftCount = $count - $page * self::AUCTION_PER_PAGE;
        /** @var MainPage $mainPage */
        $mainPage = $this->getDoctrine()->getRepository(MainPage::class)->findAll()[0];

        $lastFinishedAuctions = $this->getProductRepository()->findLastEnded();
        $currentAuctions = $productParser->parserProducts($this->getProductRepository()->findCurrectAuctions($filterParams, self::AUCTION_PER_PAGE, $offset));

        if($page > 1 && !count($currentAuctions)){
            return $this->redirectToRoute("list_products", $request->query->all());
        }

        return $this->render('client/main.html.twig', [
            "sliderImages" => $mainPage->getSliderImages(),
            "soonProduct" => $mainPage->getSoonProduct(),
            "lastFinishedAuctions" => $lastFinishedAuctions,
            "currentAuctions" => $currentAuctions,
            "page" => $page,
            "leftCount" => $leftCount,
            "allCategories" => Product::$allCategories,
            "query" => $query ? '?' . $query : '',
        ]);
    }

    protected function parseQueryParams($filterParams)
    {
        $filterParams = is_array($filterParams) ? $filterParams : [];

        if(!array_key_exists("categories", $filterParams) || !is_array($filterParams["categories"]) || in_array("all", $filterParams["categories"])){
            $filterParams["categories"] = [];
        }

        if(!array_key_exists("times", $filterParams) || !is_array($filterParams["times"]) || in_array("all", $filterParams["times"]) ||  in_array("active", $filterParams["times"]) &&  in_array("soon", $filterParams["times"])){
            $filterParams["times"] = [];
        }

        return $filterParams;
    }
}