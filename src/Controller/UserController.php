<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
    /**
     * @Route("/my-auctions", name="profile_my_auctions")
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function myAuctionsShowAction(Request $request)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN")){
            return $this->redirectToRoute("list_products");
        }

        return $this->render('client/profile/my-auctions.html.twig', []);
    }
}