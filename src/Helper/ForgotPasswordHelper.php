<?php

namespace App\Helper;


use App\Entity\ForgotPassword;
use App\Entity\User;
use App\Generator\PasswordGenerator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Date;

class ForgotPasswordHelper
{
    const NOT_FOUND_USER = "Пользователь с таким email отсутствует.";
    const INCORRECT_DATA = "Некорректные данные.";
    const INCORRECT_CODE = "Некорректный код.";
    const EMPTY_PASSWORD = "Пароль не должен быть пустым.";
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PasswordGenerator
     */
    private $generator;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * ForgotPasswordHelper constructor.
     * @param EntityManagerInterface $em
     * @param PasswordGenerator $generator
     * @param Swift_Mailer $mailer
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $em, PasswordGenerator $generator, Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->generator = $generator;
        $this->mailer = $mailer;
        $this->encoder = $encoder;
    }

    public function processForm(FormInterface $form)
    {
        $step = $form->get("step")->getData();

        switch($step){
            case "1":
                return $this->processStep1($form);
            case "2":
                return $this->processStep2($form);
            case "3":
                return $this->processStep3($form);
            default:
                $form->get("step")->addError(new FormError(self::INCORRECT_DATA));

                return $form;
        }
    }

    protected function processStep1(FormInterface $form)
    {
        $email = $form->get("email")->getData();

        $user = $this->em->getRepository(User::class)->findOneBy(["email" => $email]);

        if(!($user instanceof User)){
            $form->get("email")->addError(new FormError(self::NOT_FOUND_USER));
        }
        else{
            $forgotPassword = $user->getForgotPassword();

            if(!($forgotPassword instanceof ForgotPassword)){
                $forgotPassword = new ForgotPassword($user);

                $this->em->persist($forgotPassword);
            }

            $forgotPassword->setCode($this->generator->generateOnlyNumberCode());
            $forgotPassword->setCreatedAt(new DateTime());

            $this->em->flush();

            $this->sendEmail($forgotPassword);

        }

        return $form;
    }

    protected function processStep2(FormInterface $form)
    {
        $email = $form->get("email")->getData();
        $code = $form->get("code")->getData();

        $user = $this->em->getRepository(User::class)->findOneBy(["email" => $email]);

        if(!($user instanceof User)){
            $form->get("email")->addError(new FormError(self::NOT_FOUND_USER));
        }
        elseif(!($user->getForgotPassword() instanceof ForgotPassword)){
            $form->get("code")->addError(new FormError(self::INCORRECT_CODE));
        }
        else{
            /** @var ForgotPassword $forgotPassword */
            $forgotPassword = $user->getForgotPassword();

            if($code !== $forgotPassword->getCode() || !$this->isCodeLive($forgotPassword)){
                $form->get("code")->addError(new FormError(self::INCORRECT_CODE));
            }
        }

        return $form;
    }

    protected function processStep3(FormInterface $form)
    {
        $email = $form->get("email")->getData();
        $password = $form->get("password")->getData();

        $user = $this->em->getRepository(User::class)->findOneBy(["email" => $email]);

        if(!($user instanceof User)){
            $form->get("email")->addError(new FormError(self::NOT_FOUND_USER));
        }
        if(!$password){
            $form->get("password")->addError(new FormError(self::EMPTY_PASSWORD));
        }
        else{
            $user->setPassword($this->encoder->encodePassword($user, $password));

            $this->em->flush();
        }

        return $form;
    }

    protected function sendEmail(ForgotPassword $forgotPassword)
    {
        $message = (new \Swift_Message('Recovery password'))
            ->setTo($forgotPassword->getUser()->getEmail())
            ->addFrom("info@lucky-deal.ru")
            ->setBody(
                "Код для восстановления пароля: " . $forgotPassword->getCode()
            );

        $this->mailer->send($message);
    }

    protected function isCodeLive(ForgotPassword $forgotPassword)
    {
        $now = new DateTime();

        $difference = $now->getTimestamp() - $forgotPassword->getCreatedAt()->getTimestamp();

        return $difference < ForgotPassword::TTL;
    }
}