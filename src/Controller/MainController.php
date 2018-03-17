<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\MainPage;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/test-dev")
 */
class MainController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function mainPageAction(Request $request)
    {
        /** @var MainPage $mainPage */
        $mainPage = $this->getDoctrine()->getRepository(MainPage::class)->findAll()[0];

        $lastFinishedAuctions = $this->getProductRepository()->findLastEnded();
        $currentAuctions = $this->getProductRepository()->findCurrectAuctions();

        return $this->render('client/main.html.twig', [
            "sliderImages" => $mainPage->getSliderImages(),
            "soonProduct" => $mainPage->getSoonProduct(),
            "lastFinishedAuctions" => $lastFinishedAuctions,
            "currentAuctions" => $currentAuctions
        ]);
    }
}