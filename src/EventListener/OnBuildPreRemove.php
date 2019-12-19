<?php

namespace App\EventListener;

use App\Entity\Build;
use App\Storage\BuildStorage;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class OnBuildPreRemove
{
    private $buildStorage;

    public function __construct(BuildStorage $buildStorage)
    {
        $this->buildStorage = $buildStorage;
    }

    public function preRemove(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        if (!$entity instanceof Build) {
            return;
        }

        $this->buildStorage->deleteDir($entity->getStoragePath());
    }

}
