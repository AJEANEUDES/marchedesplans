<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaysRepository::class)
 */
class Pays
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $alpha2;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $alpha3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_en_gb;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_fr_fr;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="pays")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    public function __toString(){
        // to show the name of the Category in the select
        return $this->nom_fr_fr;
        // to show the id of the Category in the select
        // return $this->id;
    }
   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(?int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAlpha2(): ?string
    {
        return $this->alpha2;
    }

    public function setAlpha2(?string $alpha2): self
    {
        $this->alpha2 = $alpha2;

        return $this;
    }

    public function getAlpha3(): ?string
    {
        return $this->alpha3;
    }

    public function setAlpha3(?string $alpha3): self
    {
        $this->alpha3 = $alpha3;

        return $this;
    }

    public function getNomEnGb(): ?string
    {
        return $this->nom_en_gb;
    }

    public function setNomEnGb(string $nom_en_gb): self
    {
        $this->nom_en_gb = $nom_en_gb;

        return $this;
    }

    public function getNomFrFr(): ?string
    {
        return $this->nom_fr_fr;
    }

    public function setNomFrFr(string $nom_fr_fr): self
    {
        $this->nom_fr_fr = $nom_fr_fr;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setPays($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getPays() === $this) {
                $user->setPays(null);
            }
        }

        return $this;
    }

   
}
