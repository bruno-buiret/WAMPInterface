<?php

namespace App\Repository;

use App\Entity\VirtualHost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class VirtualHostRepository
 *
 * @package App\Repository
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 */
class VirtualHostRepository extends ServiceEntityRepository
{
    /**
     * {@inheritDoc}
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VirtualHost::class);
    }
}
