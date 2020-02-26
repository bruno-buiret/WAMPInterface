<?php

namespace App\Repository;

use App\Entity\Shortcut;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * @method Shortcut|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shortcut|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shortcut[]    findAll()
 * @method Shortcut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortcutRepository extends SortableRepository
{
    /**
     * {@inheritDoc}
     */
    public function __construct(ManagerRegistry $registry)
    {
        $entityManager = $registry->getManagerForClass(Shortcut::class);

        parent::__construct($entityManager, $entityManager->getClassMetadata(Shortcut::class));
    }
}
