<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\LoginType;
use App\Helper\LoginHelper;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\RememberMe\TokenBasedRememberMeServices;

class SecurityController extends BaseController
{
    /**
     * @Route("/registration", name="register")
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
//        $user = new InsuranceClient();
//
//        $form = $this->createForm(UserType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $password = $this->get('security.password_encoder')
//                ->encodePassword($user, $user->getPassword());
//
//            $user->setPassword($password);
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($user);
//            $em->flush();
//
//            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
//            $this->get('security.token_storage')->setToken($token);
//            $this->get('session')->set('_security_main', serialize($token));
//
//            return new JsonResponse(['success' => true]);
//        }

        return $this->render('patient/edit.html.twig', [
            //'form' => $form->createView(),
        ]);
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
}