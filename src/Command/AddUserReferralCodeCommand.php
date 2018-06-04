<?php

namespace App\Command;


use App\Entity\User;
use App\Generator\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddUserReferralCodeCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:referral:add-codes')
            ->setDescription('Add referral code to user');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $generator = new PasswordGenerator();
        $container = $this->getContainer();
        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine.orm.default_entity_manager');

        $users = $em->getRepository(User::class)->findAll();

        /** @var User $user */
        foreach($users as $user){
            if($user->hasRole(User::ROLE_SUPER_ADMIN)){
                continue;
            }

            $user->setReferralCode($generator->generateNumberWordCode(10));
        }

        $em->flush();
    }
}