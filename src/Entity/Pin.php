<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PinRepository;
use App\Entity\Traits\Timestampable;

/**
 * @ORM\Entity(repositoryClass=PinRepository::class)
 * @ORM\Table(name="pins")
 * @ORM\HasLifecycleCallbacks
 */

class Pin
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimeStamps()
    {
        if ($this->createdAt === null){
            //si on a déjà une valeur durant la création, on ne doit plus modifier cette valeur
            $this->setCreatedAt(New \DateTimeImmutable);
        }
        
        $this->setUpdatedAt(New \DateTimeImmutable);        
    }
}
