<?php

namespace App\MsAuth\Services;

use App\MsAuth\Repositories\UserRepository;
use App\MsAuth\Models\User;
use App\Redis\RedisService;

const USERNAME_ALREADY_EXISTS = "Username already exists";

class UserService
{
    private UserRepository $userRepository;
    private RedisService $redisService;

    private JwtService $jwtService;


    public function __construct(UserRepository $userRepository, RedisService $redisService)
    {
        $this->userRepository = $userRepository;
        $this->redisService = $redisService;
        $this->jwtService = new JwtService("key");
    }

    public function registerUser(string $username, string $password):User|string
    {
        $user = new User();
        $user->setUsername($username);

        $checkUser = $this->userRepository->findByUsername($username);
        if (!is_null($checkUser)) {
            return USERNAME_ALREADY_EXISTS;
        }

        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));

        $this->userRepository->save($user);

        return $user;
    }

    public function authenticateUser(string $username, string $password): ?array
    {
        $user = $this->userRepository->findByUsername($username);

        if ($user && password_verify($password, $user->getPassword())) {
            // check existing token
            $key = $username.$user->getId();
            $exp = 3600;
            $payload = array('username' => $username,'user_id' => $user->getId());
            $result = $this->redisService->get($key);
            if (is_null($result)) {
                $accessToken = $this->jwtService->generateToken($payload, $exp);
                $payload['access_token'] = $accessToken;
                $this->redisService->set($key, $accessToken, $exp);
            } else {
                $payload['access_token'] = $result;
            }
            return $payload;
        }
        return null;
    }

    public function getProfile(string $username): ?User
    {
        $result = $this->userRepository->findByUsername($username);
        $user = new User();
        $user->setUsername($username);
        $user->setId($result->getId());
        return $user;
    }
}
