<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class MeController
{
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $doctrine)
    {
        $this->security = $security;
        $this->entityManager = $doctrine;
    }

    public function __invoke()
    {
        $user = $this->security->getUser();
        $repository = $this->entityManager->getRepository(User::class);

        return $repository->findOneByEmail($user->getUsername());
    }
}
