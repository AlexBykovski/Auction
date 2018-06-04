<?php

namespace App\Helper;


use App\Entity\User;
use App\Generator\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationHelper
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * RegistrationHelper constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addReferrer(string $code, User $user)
    {
        $referrer = $this->em->getRepository(User::class)->findOneBy(["referralCode" => $code]);

        if(!($referrer instanceof User)){
            return false;
        }

        $user->setReferrer($referrer);
        $referrer->addReferral($user);

        return true;
    }

    public function addReferralCode(User $user)
    {
        $generator = new PasswordGenerator();

        $user->setReferralCode($generator->generateNumberWordCode(10));
    }
}