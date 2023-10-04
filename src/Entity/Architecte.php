<?php

namespace App\Entity;

use App\Repository\ArchitecteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchitecteRepository::class)
 */
class Architecte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $num_banque;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumBanque(): ?string
    {
        return $this->num_banque;
    }

    public function setNumBanque(string $num_banque): self
    {
        $this->num_banque = $num_banque;

        return $this;
    }
}
