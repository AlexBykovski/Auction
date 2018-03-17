<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\DeliveryPage;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/test-dev")
 */
class StaticController extends BaseController
{
    /**
     * @Route("/how-it-works", name="how_it_works")
     */
    public function howItWorksAction(Request $request)
    {
        return $this->render('client/how-it-works.html.twig', []);
    }

    /**
     * @Route("/delivery-help", name="delivery_help")
     */
    public function deliveryHelpAction(Request $request)
    {
        /** @var DeliveryPage $deliveryPage */
        $deliveryPage = $this->getDoctrine()->getRepository(DeliveryPage::class)->findAll()[0];

        return $this->render('client/delivery-help.html.twig', [
            "delivery" => $deliveryPage
        ]);
    }
}