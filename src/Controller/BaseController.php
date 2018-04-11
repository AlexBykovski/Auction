<?php

namespace App\Controller;

use App\Entity\AutoStake;
use App\Entity\Product;
use App\Entity\StakeExpense;
use App\Entity\StakePurchase;
use App\Repository\ProductRepository;
use App\Repository\StakeExpenseRepository;
use App\Repository\StakePurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * @return ProductRepository
     */
    public function getProductRepository()
    {
        return $this->getDoctrine()->getRepository(Product::class);
    }

    /**
     * @return StakePurchaseRepository
     */
    public function getStakePurchaseRepository()
    {
        return $this->getDoctrine()->getRepository(StakePurchase::class);
    }

    /**
     * @return StakeExpenseRepository
     */
    public function getStakeExpenseRepository()
    {
        return $this->getDoctrine()->getRepository(StakeExpense::class);
    }

    /**
     * @return AutoStakeRepository
     */
    public function getAutoStakeRepository()
    {
        return $this->getDoctrine()->getRepository(AutoStake::class);
    }
}