<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\AboutUsPage;
use App\Entity\BonusPage;
use App\Entity\DeliveryPage;
use App\Entity\FAQ;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class StaticController extends BaseController
{
    /**
     * @Route("/how-it-works", name="how_it_works")
     */
    public function howItWorksPageShowAction(Request $request)
    {
        return $this->render('client/static/how-it-works.html.twig', []);
    }

    /**
     * @Route("/delivery-help", name="delivery_help")
     */
    public function deliveryHelpPageShowAction(Request $request)
    {
        /** @var DeliveryPage $deliveryPage */
        $deliveryPage = $this->getDoctrine()->getRepository(DeliveryPage::class)->findAll()[0];

        return $this->render('client/static/delivery-help.html.twig', [
            "delivery" => $deliveryPage
        ]);
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function FAQPageShowAction(Request $request)
    {
        $faqs = $this->getDoctrine()->getRepository(FAQ::class)->findAll();

        return $this->render('client/static/faq.html.twig', [
            "faqs" => $faqs
        ]);
    }

    /**
     * @Route("/bonuses", name="bonus_page")
     */
    public function bonusPageShowAction(Request $request)
    {
        /** @var BonusPage $bonusPage */
        $bonusPage = $this->getDoctrine()->getRepository(BonusPage::class)->findAll()[0];

        return $this->render('client/static/bonus.html.twig', [
            "bonusPage" => $bonusPage
        ]);
    }

    /**
     * @Route("/about-us", name="about_us")
     */
    public function aboutUsPageShowAction(Request $request)
    {
        /** @var AboutUsPage $aboutUsPage */
        $aboutUsPage = $this->getDoctrine()->getRepository(AboutUsPage::class)->findAll()[0];

        return $this->render('client/static/about-us.html.twig', [
            "aboutUsPage" => $aboutUsPage
        ]);
    }
}