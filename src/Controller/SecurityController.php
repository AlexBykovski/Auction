<?php

namespace App\Controller;

use App\Entity\NotificationDetail;
use App\Entity\StakeBalance;
use App\Entity\StakeDetail;
use App\Entity\User;
use App\Entity\UserDeliveryDetail;
use App\Form\Type\LoginType;
use App\Form\Type\RegistrationType;
use App\Form\Type\ForgotPasswordType;
use App\Helper\ForgotPasswordHelper;
use App\Helper\LoginHelper;
use App\Helper\RegistrationHelper;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends BaseController
{
    /**
     * @Route("/registration", name="register")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param RegistrationHelper $registrationHelper
     * @param LoginHelper $loginHelper
     *
     * @return Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder, RegistrationHelper $registrationHelper, LoginHelper $loginHelper)
    {
        $em = $this->getDoctrine()->getManager();
        $referrer = $request->headers->get('referer');
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $errorsForm = [[
            "element" => "email",
            "message" => "Неверные данные",
        ]];

        if($this->getUser() instanceof User){
            return $loginHelper->loginSuccessfullyResponse($this->getUser(), "User has already logged in.");
        }

        if(!$referrer || !parse_url($referrer) || parse_url($referrer)["host"] !== $request->getHost()){
            $errorsForm[0]["message"] = "Incorrect host.";

            return $this->getRegistrationResponseWithForm($form, $errorsForm);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $getNews = $form->get("get_news")->getData();

            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setDeliveryDetail(new UserDeliveryDetail());
            $notificationDetail = new NotificationDetail();
            $notificationDetail->setNews($getNews);
            $user->setNotificationDetail(new NotificationDetail());

            $stakeDetail = new StakeDetail();
            $stakeBalance = new StakeBalance();
            $stakeDetail->setStakeBalance($stakeBalance);
            $stakeBalance->setStakeDetail($stakeDetail);
            $stakeDetail->addStakes(StakeBalance::REGISTRATION_STAKES, StakeBalance::COUNT_REGISTRATION_STAKES);

            $user->setStakeDetail($stakeDetail);
            $user->setPassword($password);

            if($request->query->has("ref") && ($refCode = $request->query->get("ref"))) {
                $registrationHelper->addReferrer($refCode, $user);
            }

            $registrationHelper->addReferralCode($user);

            $em->persist($stakeBalance);
            $em->persist($user);
            $em->flush();

            return $loginHelper->login($user, $request, false);
        }
        elseif($form->isSubmitted() && !$form->isValid()){
            return $this->getRegistrationResponseWithForm($form);
        }

        return $this->getRegistrationResponseWithForm($form);
    }

    /**
     * @Route("/login-user", name="login_simple_user")
     */
    public function loginAction(Request $request, UserPasswordEncoderInterface $encoder, LoginHelper $loginHelper)
    {
        $referrer = $request->headers->get('referer');
        $form = $this->createForm(LoginType::class);
        $errorsForm = [[
            "element" => "remember_me",
            "message" => "Неверные данные",
        ]];

        if($this->getUser() instanceof User){
            return $loginHelper->loginSuccessfullyResponse($this->getUser(), "User has already logged in.");
        }

        if(!$referrer || !parse_url($referrer) || parse_url($referrer)["host"] !== $request->getHost()){
            $errorsForm[0]["message"] = "Incorrect host.";

            return $this->getLoginResponseWithForm($form, $errorsForm);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get("username")->getData();
            $password = $form->get("password")->getData();
            $rememberMe = $form->get("remember_me")->getData();

            $user = $loginHelper->isValidCredential($encoder, $username, $password);

            if($user instanceof User){
                return $loginHelper->login($user, $request, $rememberMe);
            }

            return $this->getLoginResponseWithForm($form, $errorsForm);
        }
        elseif($form->isSubmitted() && !$form->isValid()){
            $form->get("remember_me")->addError(new FormError("Неверные данные"));

            return $this->getLoginResponseWithForm($form, $errorsForm);
        }

        return $this->getLoginResponseWithForm($form);
    }

    /**
     * @Route("/forgot-password", name="forgot_password")
     */
    public function forgotPasswordAction(Request $request, ForgotPasswordHelper $helper)
    {
        if($this->getUser() instanceof User){
            return new JsonResponse([
                "success" => true,
                "isRefresh" => true,
            ]);
        }

        $form = $this->createForm(ForgotPasswordType::class);

        $form->handleRequest($request);
        $nextStep = $form->get("step")->getData() ? (int)$form->get("step")->getData() : 1;

        if ($form->isSubmitted() && $form->isValid()) {
            $form = $helper->processForm($form);

            if(!$form->getErrors(true)->count()){
                ++$nextStep;
            }
        }

        return $this->render(
            'client/security/forgot_password.html.twig',
            [
                "form" => $form->createView(),
                "step" => $nextStep,
            ]
        );
    }

    public function getLoginResponseWithForm(FormInterface $form, array $errors = [])
    {
        foreach($errors as $error){
            $form->get($error["element"])->addError(new FormError($error["message"]));
        }

        return $this->render(
            'client/security/login.html.twig',
            [
                "form" => $form->createView(),
            ]
        );
    }

    public function getRegistrationResponseWithForm(FormInterface $form, array $errors = [])
    {
        foreach($errors as $error){
            $form->get($error["element"])->addError(new FormError($error["message"]));
        }

        return $this->render(
            'client/security/registration.html.twig',
            [
                "form" => $form->createView(),
            ]
        );
    }
}