<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductDeliveryDetail;
use App\Entity\StakeDetail;
use App\Entity\User;
use App\Entity\UserDeliveryDetail;
use App\Form\Type\ProductDeliveryType;
use App\Form\Type\UserProfileType;
use App\Form\Type\UserSupportType;
use App\Helper\DeliveryDetailHelper;
use App\Helper\LoginHelper;
use App\Helper\ResizeImageHelper;
use App\Helper\UserSupportHelper;
use App\Parser\ProductParser;
use App\Upload\FileUpload;
use Imagick;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profile")
 */
class UserController extends BaseController
{
    const DEFAULT_COUNT_MY_AUCTIONS = 50;
    const LIMIT_SHOW_EXPENSES = 50;
    const LIMIT_SHOW_PURCHASES = 50;

    /**
     * @Route("/auctions", name="profile_my_auctions")
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
     * @Route("/support", name="profile_user_support")
     */
    public function userSupportAction(Request $request)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN")){
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
    public function createOrderAction(Request $request, Product $auction, DeliveryDetailHelper $deliveryDetailHelper)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN") || !$this->isGranted("ROLE_USER")){
            return $this->redirectToRoute("list_products");
        }

        $em = $this->getDoctrine()->getManager();

        $currentDeliveryDetail = $em->getRepository(ProductDeliveryDetail::class)->findOneBy(["product" => $auction, "user" => $this->getUser()]);

        $isCreateAction = !($currentDeliveryDetail instanceof ProductDeliveryDetail);
        $deliveryDetail = $isCreateAction ? new ProductDeliveryDetail() : $currentDeliveryDetail;
        $userDeliveryDetail = $this->getUser()->getDeliveryDetail();

        if($isCreateAction && $userDeliveryDetail instanceof UserDeliveryDetail) {
            $deliveryDetail->setUserDeliveryDetail($userDeliveryDetail);
        }

        if($isCreateAction){
            $deliveryDetail->setCost($deliveryDetailHelper->getUserCostForAuction($this->getUser(), $auction));
        }

        $form = $this->createForm(ProductDeliveryType::class, $deliveryDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $link = $this->getPaymentLink($deliveryDetail->getPayment());

            if($isCreateAction) {
                $em->persist($deliveryDetail);
                $auction->addDeliveryDetail($deliveryDetail);
            }

            $em->flush();

            return $this->render('client/profile/create-order.html.twig', [
                "form" => $form->createView(),
                "auction" => $auction,
                "goodOrder" => true,
                "link" => $link,
                "cost" => $deliveryDetail->getCost(),
            ]);
        }

        return $this->render('client/profile/create-order.html.twig', [
            "form" => $form->createView(),
            "auction" => $auction,
            "cost" => $deliveryDetail->getCost(),
        ]);
    }

    /**
     * @Route("/private-data", name="profile_private_data")
     */
    public function privateDataAction(Request $request, UserPasswordEncoderInterface $encoder, LoginHelper $loginHelper)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN") || !$this->isGranted("ROLE_USER")){
            return $this->redirectToRoute("list_products");
        }
        /** @var ResizeImageHelper $resizeImageHelper */
        $resizeImageHelper = $this->get("app.helper.resize_image_helper");

        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get("photoFile")->getData();
            $oldPassword = $form->get("oldPassword")->getData();
            $newPassword = $form->get("newPassword")->getData();

            if($file instanceof UploadedFile){
                $imageBlob = $resizeImageHelper->getBlobUserProfileResizeImage($file);
                $pathImage = $resizeImageHelper->uploadBlobFile($file, $imageBlob, FileUpload::USER_PHOTO);

                $user->setPhoto($pathImage);
            }

            if($oldPassword){
                $userFind = $loginHelper->isValidCredential($encoder, $user->getUsername(), $oldPassword);

                if(!($userFind instanceof User)){
                    $form->get("oldPassword")->addError(new FormError("Неверный старый пароль"));
                }
                else{
                    if(!$newPassword){
                        $form->get("newPassword")->get("first")->addError(new FormError("Новый пароль не должен быть пустым"));
                    }
                    else{
                        $user->setPassword($encoder->encodePassword($user, $newPassword));
                    }
                }
            }

            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('client/profile/private-data.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/stakes", name="profile_stakes")
     */
    public function stakesShowAction(Request $request)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN") || !$this->isGranted("ROLE_USER")){
            return $this->redirectToRoute("list_products");
        }

        /** @var StakeDetail $stakeDetail */
        $stakeDetail = $this->getUser()->getStakeDetail();

        $expenses = $this->getStakeExpenseRepository()->findBy(["stakeDetail" => $stakeDetail], ["createdAt" => "DESC"], self::LIMIT_SHOW_EXPENSES);
        $purchases = $this->getStakePurchaseRepository()->findBy(["stakeDetail" => $stakeDetail], ["createdAt" => "DESC"], self::LIMIT_SHOW_PURCHASES);

        return $this->render('client/profile/stakes.html.twig', [
            "expenses" => $expenses,
            "purchases" => $purchases,
        ]);
    }

    /**
     * @Route("/cut-image", name="profile_cut_image")
     */
    public function cutImageAction(Request $request)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN") || !$this->isGranted("ROLE_USER")){
            return $this->redirectToRoute("list_products");
        }

        /** @var ResizeImageHelper $resizeImageHelper */
        $resizeImageHelper = $this->get("app.helper.resize_image_helper");
        /** @var UploadedFile $image */
        $image = $request->files->get("image");

        return new JsonResponse([
            'result' => base64_encode($resizeImageHelper->getBlobUserProfileResizeImage($image)),
        ], 200);
    }

    protected function getPaymentLink($type)
    {
        switch($type){
            case "card":
                return "https://www.robokassa.ru/ru/Creditcards.aspx";
            case "qiwi":
                return "https://www.robokassa.ru/ru/Terminals.aspx";
            case "ipay":
                return "http://www.ipay.by/";
            case "yandex":
                return "https://www.robokassa.ru/ru/Currencies.aspx";
            default:
                return "https://www.robokassa.ru/ru/Creditcards.aspx";
        }
    }
}