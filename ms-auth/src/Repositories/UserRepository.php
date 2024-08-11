<?php

namespace App\MsAuth\Repositories;

use Doctrine\ORM\EntityRepository;
use App\MsAuth\Models\User;

class UserRepository extends EntityRepository
{
    public function findByUsername(string $username): ?User
    {
        return $this->findOneBy(['username' => $username]);
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
