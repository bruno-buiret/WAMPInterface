<?php

namespace App\Repository;

use App\Entity\VirtualHost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class VirtualHostRepository
 *
 * @package App\Repository
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 */
class VirtualHostRepository extends ServiceEntityRepository
{
    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VirtualHost::class);
    }
}
