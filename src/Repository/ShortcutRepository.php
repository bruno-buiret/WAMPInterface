<?php

namespace App\Repository;

use App\Entity\Shortcut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Shortcut|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shortcut|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shortcut[]    findAll()
 * @method Shortcut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortcutRepository extends SortableRepository
{
    public function __construct(RegistryInterface $registry)
    {
        $entityManager = $registry->getEntityManagerForClass(Shortcut::class);

        parent::__construct($entityManager, $entityManager->getClassMetadata(Shortcut::class));
    }
}
