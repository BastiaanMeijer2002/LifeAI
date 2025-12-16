<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Workout;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security; 

final class CurrentUserWorkoutExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(private Security $security) {}

    /**
     * Apply filtering by current user unless admin
     */
    private function addWhere(QueryBuilder $qb, QueryNameGeneratorInterface $qng, string $resourceClass): void
    {
        if ($resourceClass !== Workout::class) {
            return;
        }

        $user = $this->security->getUser();

        if (!$user) {
            $alias = $qb->getRootAliases()[0];
            $qb->andWhere("$alias.id IS NULL"); // no results
            return;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        $alias = $qb->getRootAliases()[0];
        $param = $qng->generateParameterName('owner');

        $qb
            ->andWhere("$alias.owner = :$param")
            ->setParameter($param, $user);
    }

    public function applyToCollection(
        QueryBuilder $qb,
        QueryNameGeneratorInterface $qng,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = []
    ): void {
        $this->addWhere($qb, $qng, $resourceClass);
    }

    public function applyToItem(
        QueryBuilder $qb,
        QueryNameGeneratorInterface $qng,
        string $resourceClass,
        array $identifiers,
        ?Operation $operation = null,
        array $context = []
    ): void {
        $this->addWhere($qb, $qng, $resourceClass);
    }
}
