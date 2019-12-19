<?php

namespace App\Doctrine\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Build;
use App\Entity\Offer;
use App\Entity\Repository;
use App\Entity\User;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

class UserCollectionExtension implements QueryCollectionExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        /** @var User $user */
        if (
            User::class !== $resourceClass
            || $this->security->isGranted('ROLE_ADMIN')
            || null === $user = $this->security->getUser()
        ) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $joinAlias = $queryNameGenerator->generateJoinAlias('repositories');
        $queryBuilder->innerJoin(
            "$rootAlias.repositories",
            $joinAlias,
            Join::WITH,
            $queryBuilder->expr()->in("$joinAlias.id", ':repositories')
        );
        $queryBuilder->setParameter('repositories', $user->getRepositories()->toArray());
    }
}
