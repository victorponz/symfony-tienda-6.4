<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService{
    private $repository;
    private $passwordHasher;

    public function __construct(UserRepository $repository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->repository = $repository;
        $this->passwordHasher = $passwordHasher;
    }
    public function register(User $user, string $plainPassword): void{
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
        $this->repository->save($user, true);
    }
}
