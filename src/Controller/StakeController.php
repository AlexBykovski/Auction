<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\StakeExpense;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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

        $stakeExpense = new StakeExpense();
        $stakeExpense->setCount(1);
        $stakeExpense->setProduct($product);
        $stakeExpense->setStakeDetail($user->getStakeDetail());

        $em->persist($stakeExpense);

        $product->getTimer()->restartTimer();

        $em->flush();

        return new JsonResponse([
            "success" => true,
        ]);
    }
}