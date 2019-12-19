<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\UuidableEntity;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "security"="is_granted('ROLE_USER')",
 *      },
 *      collectionOperations={
 *          "get"={},
 *          "post"={},
 *      },
 *      itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN') or user in object.getRepository().getUsers()",
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN') or user in object.getRepository().getUsers()",
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_ADMIN') or user in object.getRepository().getUsers()",
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN') or user in object.getRepository().getUsers().toArray()",
 *          },
 *      }
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "id": "exact",
 *          "branch": "exact",
 *          "storagePath": "exact",
 *          "repository.id": "exact",
 *          "repository.url": "exact",
 *      }
 * )
 * @ApiFilter(OrderFilter::class)
 * @ORM\Entity(repositoryClass="App\Repository\BuildRepository")
 */
class Build
{
    use UuidableEntity;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $branch;

    /**
     * @var Repository
     * @ORM\ManyToOne(targetEntity="App\Entity\Repository", cascade={"persist"}, inversedBy="builds")
     */
    private $repository;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $storagePath;

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function setBranch(string $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

    public function getRepository(): ?Repository
    {
        return $this->repository;
    }

    public function setRepository(?Repository $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    public function getStoragePath(): ?string
    {
        return $this->storagePath;
    }

    public function setStoragePath(string $storagePath): self
    {
        $this->storagePath = $storagePath;

        return $this;
    }
}
