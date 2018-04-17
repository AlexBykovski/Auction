<?php

namespace App\Controller;

use App\Entity\AutoStake;
use App\Entity\Product;
use App\Entity\StakeDetail;
use App\Entity\StakeExpense;
use App\Entity\StakePurchase;
use App\Entity\User;
use App\Form\Type\BuyStakesType;
use App\Helper\StakeHelper;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class StakeController extends BaseController
{
    /**
     * @Route("/make-manual-stake/{auction_id}", name="make_manual_stake_auction")
     *
     * @ParamConverter("product", class="App:Product", options={"id" = "auction_id"})
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function makeStakeAction(Request $request, Product $product)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->getUser();
        $stakeDetail = $user->getStakeDetail();

        if(!$stakeDetail || !$stakeDetail->getCount()){
            return new JsonResponse([
                "success" => false,
            ]);
        }

        $stakeDetail->setCount($stakeDetail->getCount() - 1);

        $stakeExpense = new StakeExpense();
        $stakeExpense->setCount(1);
        $stakeExpense->setProduct($product);
        $stakeExpense->setStakeDetail($stakeDetail);
        $product->setPotentialWinner($user);
        $product->setCost($product->getCost() + 0.1);

        $em->persist($stakeExpense);

        $product->getTimer()->restartTimer();

        $em->flush();

        return new JsonResponse([
            "success" => true,
        ]);
    }

    /**
     * @Route("/create-auto-stake/{auction_id}", name="create_auto_stake_auction")
     *
     * @ParamConverter("auction", class="App:Product", options={"id" = "auction_id"})
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function createAutoStakeAction(Request $request, Product $auction)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var StakeDetail $stakeDetail */
        $stakeDetail = $this->getUser()->getStakeDetail();
        $autoStakeInDB = $this->getDoctrine()->getRepository(AutoStake::class)->findOneBy(["stakeDetail" => $stakeDetail, "auction" => $auction]);
        $countUserStakes = $stakeDetail->getCount();
        $countStakes = json_decode($request->getContent(), true)["countStakes"];

        $responseData = [
            "success" => false,
            "message" => ""
        ];

        if($autoStakeInDB instanceof AutoStake){
            $responseData["message"] = "У вас уже есть автоставка на данном аукционе. Обновите страницу.";
        }
        elseif($auction->getEndAt() instanceof DateTime){
            $responseData["message"] = "Аукцион уже завершён. Обновите страницу.";
        }
        elseif(!is_numeric($countStakes)){
            $responseData["message"] = "Укажите корректное количество ставок";
        }
        elseif(intval($countStakes) < 1){
            $countStakes = intval($countStakes);

            $responseData["message"] = "Укажите положительное количество ставок";
        }
        elseif($countUserStakes < $countStakes){
            $responseData["message"] = "У вас в наличии нет указанного количества ставок";
        }
        else{
            $autoStake = new AutoStake();
            $stakeDetail->setCount($stakeDetail->getCount() - $countStakes);

            $autoStake->setStakeDetail($stakeDetail);
            $autoStake->setAuction($auction);
            $autoStake->setIsActive(true);
            $autoStake->setCount($countStakes);

            $em->persist($autoStake);
            $em->flush();
            $responseData["success"] = true;
        }

        return new JsonResponse($responseData);
    }

    /**
     * @Route("/remove-auto-stake/{auction_id}", name="remove_auto_stake_auction")
     *
     * @ParamConverter("product", class="App:Product", options={"id" = "auction_id"})
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function removeAutoStakeAction(Request $request, Product $product)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->getUser();
        $stakeDetail = $user->getStakeDetail();

        /** @var AutoStake $autoStake */
        $autoStake = $this->getAutoStakeRepository()->findOneBy(["stakeDetail" => $stakeDetail, "auction" => $product]);
        $stakeDetail->setCount($stakeDetail->getCount() + $autoStake->getCount());

        $em->remove($autoStake);
        $em->flush();

        return new JsonResponse([
            "success" => true,
        ]);
    }

    /**
     * @Route("/buy-stakes", name="buy_stakes")
     */
    public function buyStakesAction(Request $request, StakeHelper $stakeHelper)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN") || !$this->isGranted("ROLE_USER")){
            return $this->redirectToRoute("list_products");
        }

        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->getUser();
        $simpleStakes = $stakeHelper->getStakesArrayForBuyAction();
        $specialStakes = $stakeHelper->getStakesArrayForBuyAction(true);

        $form = $this->createForm(BuyStakesType::class, [
            "simpleStakes" => array_values($simpleStakes),
            "specialStakes" => array_values($specialStakes),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentType = $form->get("payment")->getData();
            $selectedSimpleStakes = $form->get("simpleStakes")->getData()->getValues();
            $selectedSpecialStakes = $form->get("specialStakes")->getData()->getValues();

            if(!(count($selectedSimpleStakes) + count($selectedSpecialStakes))){
                $form->get("specialStakes")->addError(new FormError("Вы должны выбрать минимум 1 пакет для продолжения"));
            }
            else{
                $count = $stakeHelper->getCountForBuyAction($selectedSimpleStakes, $selectedSpecialStakes);
                $cost = $stakeHelper->getCostForBuyAction($selectedSimpleStakes, $selectedSpecialStakes);

                //@@todo add payment logic, and then remove this block
                //start block
                $stakeDetail = $user->getStakeDetail();
                $stakePurchase = new StakePurchase();
                $stakePurchase->setCost($cost);
                $stakePurchase->setCount($count);
                $stakePurchase->setStakeDetail($stakeDetail);
                $stakeDetail->setCount($stakeDetail->getCount() + $count);

                $em->persist($stakePurchase);
                $em->flush();
                //end block

                $form = $this->createForm(BuyStakesType::class, [
                    "simpleStakes" => array_values($simpleStakes),
                    "specialStakes" => array_values($specialStakes),
                ]);
            }
        }

        return $this->render('client/stake/buy_stakes.html.twig', [
            "form" => $form->createView(),
            "simpleStakes" => $simpleStakes,
            "specialStakes" => $specialStakes,
        ]);
    }
}