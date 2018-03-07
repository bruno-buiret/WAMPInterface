<?php

namespace App\EntityListener;

use DateTime;

/**
 * Class TimestampableListener
 *
 * @package App\EntityListener
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 */
class TimestampableListener
{
    /**
     * Dynamically sets the entity's date of creation if it hasn't been.
     *
     * @param mixed $entity The entity being persisted.
     */
    public function prePersist($entity)
    {
        if(null === $entity->getCreatedAt())
        {
            $entity->setCreatedAt(new DateTime());
        }
    }

    /**
     * Dynamically sets the entity's date of last update.
     *
     * @param mixed $entity The entity being updated.
     */
    public function preUpdate($entity)
    {
        $entity->setLastUpdatedAt(new DateTime());
    }
}