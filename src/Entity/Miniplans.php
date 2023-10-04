<?php

namespace App\Entity;

use App\Repository\MiniplansRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MiniplansRepository::class)
 */
class Miniplans
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
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity=Plans::class, inversedBy="miniplans ")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plans;

    /**
     * @ORM\OneToMany(targetEntity=Fichiers::class, mappedBy="miniplans", cascade={"persist"}, orphanRemoval=true)
     */
    private $fichiers;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\OneToMany(targetEntity=Achat::class, mappedBy="miniplan", orphanRemoval=true)
     */
    private $achats;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="miniplans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tx_reference;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vente;

  
    public function __construct()
    {
        $this->fichiers = new ArrayCollection();
        $this->achats = new ArrayCollection();
    }

    public function __toInt()
    {
        return $this->prix;
    }
   

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPlans(): ?plans
    {
        return $this->plans;
    }

    public function setPlans(?plans $plans): self
    {
        $this->plans = $plans;

        return $this;
    }

    /**
     * @return Collection|Fichiers[]
     */
    public function getFichiers(): Collection
    {
        return $this->fichiers;
    }

    public function addFichier(Fichiers $fichier): self
    {
        if (!$this->fichiers->contains($fichier)) {
            $this->fichiers[] = $fichier;
            $fichier->setMiniplans($this);
        }

        return $this;
    }

    public function removeFichier(Fichiers $fichier): self
    {
        if ($this->fichiers->removeElement($fichier)) {
            // set the owning side to null (unless already changed)
            if ($fichier->getMiniplans() === $this) {
                $fichier->setMiniplans(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection|Achat[]
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats[] = $achat;
            $achat->setMiniplan($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getMiniplan() === $this) {
                $achat->setMiniplan(null);
            }
        }

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTxReference(): ?string
    {
        return $this->tx_reference;
    }

    public function setTxReference(?string $tx_reference): self
    {
        $this->tx_reference = $tx_reference;

        return $this;
    }

    public function getVente(): ?int
    {
        return $this->vente;
    }

    public function setVente(?int $vente): self
    {
        $this->vente = $vente;

        return $this;
    }

    
    
}