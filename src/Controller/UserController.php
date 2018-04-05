<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductDeliveryDetail;
use App\Entity\UserDeliveryDetail;
use App\Form\Type\ProductDeliveryType;
use App\Form\Type\UserSupportType;
use App\Helper\UserSupportHelper;
use App\Parser\ProductParser;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

    /**
     * @Route("/user-support", name="profile_user_support")
     */
    public function userSupportAction(Request $request)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN") || !$this->isGranted("ROLE_USER")){
            return $this->redirectToRoute("list_products");
        }

        $isGotMessage = $request->cookies->get("got-message") ? true : false;

        /** @var UserSupportHelper $helper */
        $helper = $this->get("app.helper.user_support_helper");
        $form = $this->createForm(UserSupportType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->get("message")->getData();
            $file = $form->get("file")->getData();

            $helper->saveSupportQuestion($this->getUser(), $message, $file);

            $response = $this->redirectToRoute("profile_user_support");
            $response->headers->setCookie(new Cookie("got-message", "true", strtotime('now + 2 minutes')));

            return $response;
        }

        $response = $this->render('client/profile/user-support.html.twig', [
            "form" => $form->createView(),
            "gotMessage" => $isGotMessage
        ]);
        $response->headers->clearCookie("got-message");

        return $response;
    }

    /**
     * @Route("/history", name="profile_history")
     */
    public function profileHistoryShowAction(Request $request)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN") || !$this->isGranted("ROLE_USER")){
            return $this->redirectToRoute("list_products");
        }

        $successAuctions = $this->getProductRepository()->findSuccessAuctionsByUser($this->getUser());

        return $this->render('client/profile/history.html.twig', [
            "successAuctions" => $successAuctions
        ]);
    }

    /**
     * @Route("/create-order/{id}", name="profile_create_order")
     *
     * @ParamConverter("auction", class="App:Product", options={"id" = "id"})
     */
    public function createOrderAction(Request $request, Product $auction)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN") || !$this->isGranted("ROLE_USER")){
            return $this->redirectToRoute("list_products");
        }

        $isCreateAction = !($auction->getDeliveryDetail() instanceof ProductDeliveryDetail);
        $deliveryDetail = $isCreateAction ? new ProductDeliveryDetail() : $auction->getDeliveryDetail();
        $userDeliveryDetail = $this->getUser()->getDeliveryDetail();

        if($isCreateAction && $userDeliveryDetail instanceof UserDeliveryDetail) {
            $deliveryDetail->setUserDeliveryDetail($userDeliveryDetail);
        }

        $form = $this->createForm(ProductDeliveryType::class, $deliveryDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if($isCreateAction) {
                $em->persist($deliveryDetail);
                $auction->setDeliveryDetail($deliveryDetail);
            }

            $em->flush();

            return $this->render('client/profile/create-order.html.twig', [
                "form" => $form->createView(),
                "auction" => $auction,
                "goodOrder" => true,
            ]);
        }

        return $this->render('client/profile/create-order.html.twig', [
            "form" => $form->createView(),
            "auction" => $auction,
        ]);
    }
}