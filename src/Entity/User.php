<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\UuidableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Controller\MeController;

/**
 * @ApiResource(
 *      collectionOperations={
 *          "get"={},
 *          "post"={},
 *      },
 *      itemOperations={
 *          "me"={
 *              "security"="is_granted('ROLE_ADMIN') or object == user",
 *              "method"="GET",
 *              "path"="/users/me",
 *              "controller"=MeController::class,
 *              "defaults"={
 *                  "_api_receive"=false,
 *              },
 *              "openapi_context"={
 *                  "summary"="Retrieves my user resource.",
 *                  "parameters"={
 *                      {
 *                          "in"="path",
 *                          "name"="id",
 *                          "required"="true",
 *                          "type"="string",
 *                          "value"="me",
 *                      }
 *                  }
 *              },
 *          },
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN') or object == user",
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN') or object == user",
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_ADMIN') or object == user",
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN') or object == user",
 *          },
 *      }
 * )
 * @ApiFilter(SearchFilter::class)
 * @ApiFilter(OrderFilter::class)
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    use UuidableEntity;

    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min=8)
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Repository", mappedBy="users")
     */
    private $repositories;

    public function __construct()
    {
        $this->repositories = new ArrayCollection();
        $this->roles = ['ROLE_USER'];
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Repository[]
     */
    public function getRepositories(): Collection
    {
        return $this->repositories;
    }

    public function addRepository(Repository $repository): self
    {
        if (!$this->repositories->contains($repository)) {
            $this->repositories[] = $repository;
            $repository->addUser($this);
        }

        return $this;
    }

    public function removeRepository(Repository $repository): self
    {
        if ($this->repositories->contains($repository)) {
            $this->repositories->removeElement($repository);
            $repository->removeUser($this);
        }

        return $this;
    }
}
