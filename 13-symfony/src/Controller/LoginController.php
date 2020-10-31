<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserRepository $repository, UserPasswordEncoderInterface $encoder)
    {
        $this->repository = $repository;
        $this->encoder    = $encoder;
    }
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function index(Request $request)
    {
        $body = json_decode($request->getContent());

        if (!isset($body->username) or !isset($body->password)) {
            return new JsonResponse(['error' => 'Missing parameters'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->repository->findOneBy(['username' => $body->username]);

        if ( is_null($user) or !$this->encoder->isPasswordValid($user, $body->password) ) {
            return new JsonResponse(['error' => 'Invalid username or password'], Response::HTTP_UNAUTHORIZED);
        }

        $token = JWT::encode(['username' => $user->getUsername()], 'chave1234');

        return new JsonResponse(['access_token' => $token], Response::HTTP_OK);
    }
}
