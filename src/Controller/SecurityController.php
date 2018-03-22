<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\LoginType;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Component\Form\FormError;
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
    public function loginAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if($this->getUser() instanceof User){
            return new JsonResponse([
                "success" => true,
                "message" => "User has already logged in.",
                "user" => $this->getUser()->toArray(),
            ]);
        }

        $referrer = $request->headers->get('referer');

        $form = $this->createForm(LoginType::class);

        if(!$referrer || !parse_url($referrer) || parse_url($referrer)["host"] !== $request->getHost()){
            $form->get("remember_me")->addError(new FormError("Incorrect host."));

            return $this->render(
                'client/security/login.html.twig',
                [
                    "form" => $form->createView(),
                ]
            );
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get("username")->getData();
            $password = $form->get("password")->getData();
            $rememberMe = $form->get("remember_me")->getData();

            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["email" => $username]);
            } else {
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["username" => $username]);
            }

            if(!$user){
                $form->get("remember_me")->addError(new FormError("Неверные данные"));

                return $this->render(
                    'client/security/login.html.twig',
                    [
                        "form" => $form->createView(),
                    ]
                );
            }

            $isCorrectPassword = $encoder->isPasswordValid($user, $password);

            if($isCorrectPassword){
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());

                $this->get('security.token_storage')->setToken($token);

                $this->get('session')->set('_security_main', serialize($token));

                $event = new InteractiveLoginEvent($request, $token);
                $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

                $response = new JsonResponse([
                    'success' => true,
                    'message' => "User has been login successfully",
                    "user" => $user->toArray(),
                ]);

                if($rememberMe){
                    $providerKey = 'main';
                    $securityKey = $this->getParameter("secret");

                    $rememberMeService = new TokenBasedRememberMeServices([$user], $securityKey, $providerKey, [
                            'path' => '/',
                            'name' => 'REMEMBERME',
                            'domain' => null,
                            'secure' => false,
                            'httponly' => true,
                            'lifetime' => 1209600, // 14 days
                            'always_remember_me' => true,
                            'remember_me_parameter' => 'remember_me']
                    );

                    $rememberMeService->loginSuccess($request, $response, $token);
                }
                return $response;
            }

            $form->get("remember_me")->addError(new FormError("Неверные данные"));

            return $this->render(
                'client/security/login.html.twig',
                [
                    "form" => $form->createView(),
                ]
            );
        }
        elseif($form->isSubmitted() && !$form->isValid()){
            $form->get("remember_me")->addError(new FormError("Неверные данные"));

            return $this->render(
                'client/security/login.html.twig',
                [
                    "form" => $form->createView(),
                ]
            );
        }

        return $this->render(
            'client/security/login.html.twig',
            [
                "form" => $form->createView(),
            ]
        );
    }
}