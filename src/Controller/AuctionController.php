<?php

namespace App\Controller;

use App\Entity\AutoStake;
use App\Entity\Product;
use App\Entity\User;
use App\Form\Type\AutoStakeType;
use App\Parser\ProductParser;
use DateTime;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Constraints\Date;

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
        $myAuctions = $productParser->parserProducts($this->getProductRepository()->findCurrectAuctions(self::$defaultFilter, self::COUNT_RECOMMEND_AUCTIONS, 0, $this->getUser()));

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
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->getUser();
        $autoStakeInDB = $this->getDoctrine()->getRepository(AutoStake::class)->findOneBy(["stakeDetail" => $user instanceof User ? $user->getStakeDetail() : null, "auction" => $auction]);
        $countUserStakes = $user instanceof User ? $user->getStakeDetail()->getCount() : null;
        $autoStake = $autoStakeInDB instanceof AutoStake ? $autoStakeInDB : new AutoStake();
        $form = $this->createForm(AutoStakeType::class, $autoStake);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $endAt = $form->get("endAt")->getData();
            $isWinEnd = $form->get("isWinEnd")->getData();
            $stakes = (int)$form->get("count")->getData();

            if(!($user instanceof User)){
                $form->get("count")->addError(new FormError("Для создания автоставки вам необходимо авторизоваться"));
            }
            elseif($autoStakeInDB instanceof AutoStake){
                $form->get("count")->addError(new FormError("У вас уже есть автоставка на данном аукционе"));
            }
            elseif($auction->getEndAt() instanceof DateTime){
                $form->get("count")->addError(new FormError("Аукцион уже завершён"));
            }
            elseif($auction->getStartAt() > (new DateTime())){
                $form->get("count")->addError(new FormError("Аукцион ещё не начался"));
            }
            elseif($stakes < 1){
                $form->get("count")->addError(new FormError("Укажите положительное количество ставок"));
            }
            elseif($countUserStakes < $stakes){
                $form->get("count")->addError(new FormError("У вас в наличии нет указанного количества ставок"));
            }
            elseif(!$isWinEnd && !($endAt instanceof DateTime)){
                $form->get("count")->addError(new FormError("Укажите дату окончания автоставки"));
            }
            elseif(!$isWinEnd && $endAt <= (new DateTime())){
                $form->get("count")->addError(new FormError("Дата окончания автоставки должна быть больше, чем текущее время"));
            }
            else{
                $stakeDetail = $user->getStakeDetail();
                $stakeDetail->setCount($stakeDetail->getCount() - $autoStake->getCount());

                $autoStake->setStakeDetail($stakeDetail);
                $autoStake->setAuction($auction);
                $autoStake->setIsWinEnd($isWinEnd);
                $autoStake->setIsActive(true);

                $em->persist($autoStake);
                $em->flush();
                $autoStakeInDB = $autoStake;
            }
        }

        return $this->render('client/auction/details.html.twig', [
            "auction" => $auction->toArrayMainPage(true),
            "auctionObject" => $auction,
            "form" => $form->createView(),
            "isHasAutoStake" => $autoStakeInDB instanceof AutoStake
        ]);
    }
}