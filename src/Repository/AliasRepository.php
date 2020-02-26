<?php

namespace App\Repository;

use App\Entity\Alias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Alias|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alias|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alias[]    findAll()
 * @method Alias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AliasRepository extends ServiceEntityRepository
{
    /**
     * {@inheritDoc}
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alias::class);
    }
}
