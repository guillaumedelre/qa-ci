<?php

namespace App\Storage;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class BuildStorage implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $buildStorageDir;
    private $filesystem;

    public function __construct(string $buildStorageDir, Filesystem $filesystem)
    {
        $this->buildStorageDir = $buildStorageDir;
        $this->filesystem = $filesystem;
        $this->initialize();
    }

    private function initialize()
    {
        if (!$this->filesystem->exists($this->buildStorageDir)) {
            $this->filesystem->mkdir($this->buildStorageDir);
        }
    }

    public function getAbsolutePath(string $relativePath): string
    {
        if (empty($relativePath)) {
            return $this->buildStorageDir;
        }

        return $this->buildStorageDir . DIRECTORY_SEPARATOR . $relativePath;
    }

    public function deleteDir(string $relativePath): bool
    {
        $absolutePath = $this->getAbsolutePath($relativePath);
        if (!$this->filesystem->exists($absolutePath)) {
            return false;
        }

        try {
            $this->filesystem->remove($absolutePath);
        } catch (IOExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        return true;
    }
}
