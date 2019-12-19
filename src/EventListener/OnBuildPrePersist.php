<?php

namespace App\EventListener;

use App\Entity\Build;
use App\Storage\BuildStorage;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use GitWrapper\GitWrapper;

class OnBuildPrePersist
{
    private $buildStorage;

    public function __construct(BuildStorage $buildStorage)
    {
        $this->buildStorage = $buildStorage;
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        if (!$entity instanceof Build) {
            return;
        }

        $relativePath = $entity->getRepository()->getId()->toString()
            . DIRECTORY_SEPARATOR
            . (new \DateTime())->format('Ymd-His');

        $gitWrapper = new GitWrapper();
        $git = $gitWrapper->cloneRepository(
            $entity->getRepository()->getUrl(),
            $this->buildStorage->getAbsolutePath($relativePath)
        );

        $git->fetchAll();
        $git->checkout($entity->getBranch());

        $entity->setStoragePath($relativePath);
    }

}
