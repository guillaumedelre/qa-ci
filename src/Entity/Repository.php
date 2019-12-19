<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\UuidableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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
 *              "security"="is_granted('ROLE_ADMIN') or user in object.getUsers()",
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN') or user in object.getUsers()",
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_ADMIN') or user in object.getUsers()",
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN') or user in object.getUsers()",
 *          },
 *      }
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "id": "exact",
 *          "url": "exact",
 *          "public": "exact",
 *          "users.id": "exact",
 *          "users.email": "exact",
 *      }
 * )
 * @ApiFilter(OrderFilter::class)
 * @ORM\Entity(repositoryClass="App\Repository\RepositoryRepository")
 */
class Repository
{
    use UuidableEntity;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $url;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $public = false;

    /**
     * @var Build[]
     * @ORM\OneToMany(targetEntity="App\Entity\Build", mappedBy="repository")
     */
    private $builds;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="repositories")
     * @Assert\Count(min="1")
     */
    private $users;

    public function __construct()
    {
        $this->builds = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * @param bool $public
     *
     * @return Repository
     */
    public function setPublic(bool $public): self
    {
        $this->public = $public;
        return $this;
    }

    /**
     * @return Collection|Build[]
     */
    public function getBuilds(): Collection
    {
        return $this->builds;
    }

    public function addBuild(Build $build): self
    {
        if (!$this->builds->contains($build)) {
            $this->builds[] = $build;
            $build->setRepository($this);
        }

        return $this;
    }

    public function removeBuild(Build $build): self
    {
        if ($this->builds->contains($build)) {
            $this->builds->removeElement($build);
            // set the owning side to null (unless already changed)
            if ($build->getRepository() === $this) {
                $build->setRepository(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }
}
