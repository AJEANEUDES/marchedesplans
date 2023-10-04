<?php

namespace App\Entity;

use App\Repository\FichiersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FichiersRepository::class)
 */
class Fichiers
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
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=miniplans::class, inversedBy="fichiers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $miniplans;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMiniplans(): ?miniplans
    {
        return $this->miniplans;
    }

    public function setMiniplans(?miniplans $miniplans): self
    {
        $this->miniplans = $miniplans;

        return $this;
    }

    
}