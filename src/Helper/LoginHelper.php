<?php

namespace App\Helper;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\RememberMe\TokenBasedRememberMeServices;

class LoginHelper
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * LoginHelper constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $em
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function loginSuccessfullyResponse(User $user, $message)
    {
        return new JsonResponse([
            "success" => true,
            "message" => $message,
            "user" => $user->toArray(),
        ]);
    }

    public function login(User $user, Request $request, $rememberMe)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());

        $this->container->get('security.token_storage')->setToken($token);

        $this->container->get('session')->set('_security_main', serialize($token));

        $event = new InteractiveLoginEvent($request, $token);
        $this->container->get("event_dispatcher")->dispatch("security.interactive_login", $event);

        $response = $this->loginSuccessfullyResponse($user, "User has been login successfully");

        if($rememberMe){
            $response = $this->rememberMe($user, $response, $request, $token);
        }

        return $response;
    }

    public function isValidCredential(UserPasswordEncoderInterface $encoder, $username, $password)
    {
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $user = $this->em->getRepository(User::class)->findOneBy(["email" => $username]);
        } else {
            $user = $this->em->getRepository(User::class)->findOneBy(["username" => $username]);
        }

        if(!$user){
            return false;
        }

        return $encoder->isPasswordValid($user, $password) ? $user : false;
    }

    protected function rememberMe($user, Response $response, Request $request, $token)
    {
        $providerKey = 'main';
        $securityKey = $this->container->getParameter("secret");

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

        return $response;
    }
}