<?php

namespace App\Entity\Traits;

use Ramsey\Uuid\UuidInterface;

trait UuidableEntity
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     *
     * @return UuidableEntity|string
     */
    public function setId(UuidInterface $id)
    {

        $this->id = $id;
        return $this;
    }
}
