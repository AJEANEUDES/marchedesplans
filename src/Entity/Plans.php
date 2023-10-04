<?php

namespace App\Entity;

use App\Repository\PlansRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;

use function PHPSTORM_META\type;

/**
 * @ORM\Entity(repositoryClass=PlansRepository::class)
 */
class Plans
{

    const HEAT = [
        0 => 'electric',
        1 => 'gaz'
    ];





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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbre_piece;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbre_etage;

   

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="plans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    public function __toString()
    {
        return $this->titre;
    }


    public function __toInt()
    {
        return $this->prix;
    }

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="plans", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Achat::class, mappedBy="plan", orphanRemoval=true)
     */
    private $achats;



    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="plans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;



    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $garage;

    /**
     * @ORM\ManyToOne(targetEntity=Forme::class, inversedBy="plans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $forme;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vente;

    /**
     * @ORM\OneToMany(targetEntity=Miniplans::class, mappedBy="plans", orphanRemoval=true)
     */
    private $miniplans;

    /**
     * @ORM\ManyToOne(targetEntity=Superficie::class, inversedBy="plans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $superficie;


    public function __construct()
    {
        $this->images = new ArrayCollection();
        // $this -> type = new ArrayCollection();
        $this->achat = new ArrayCollection();
        $this->achats = new ArrayCollection();
        $this->miniplans = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbrePiece(): ?int
    {
        return $this->nbre_piece;
    }

    public function setNbrePiece(int $nbre_piece): self
    {
        $this->nbre_piece = $nbre_piece;

        return $this;
    }

    public function getNbreEtage(): ?int
    {
        return $this->nbre_etage;
    }

    public function setNbreEtage(int $nbre_etage): self
    {
        $this->nbre_etage = $nbre_etage;

        return $this;
    }

 
    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setPlans($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPlans() === $this) {
                $image->setPlans(null);
            }
        }

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
            $achat->setPlan($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getPlan() === $this) {
                $achat->setPlan(null);
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



    public function getGarage(): ?int
    {
        return $this->garage;
    }

    public function setGarage(?int $garage): self
    {
        $this->garage = $garage;

        return $this;
    }

    public function getForme(): ?Forme
    {
        return $this->forme;
    }

    public function setForme(?Forme $forme): self
    {
        $this->forme = $forme;

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

    /**
     * @return Collection|Miniplans[]
     */
    public function getMiniplans(): Collection
    {
        return $this->miniplans;
    }

    public function addMiniplan(Miniplans $miniplan): self
    {
        if (!$this->miniplans->contains($miniplan)) {
            $this->miniplans[] = $miniplan;
            $miniplan->setPlans($this);
        }

        return $this;
    }

    public function removeMiniplan(Miniplans $miniplan): self
    {
        if ($this->miniplans->removeElement($miniplan)) {
            // set the owning side to null (unless already changed)
            if ($miniplan->getPlans() === $this) {
                $miniplan->setPlans(null);
            }
        }

        return $this;
    }

    public function getSuperficie(): ?Superficie
    {
        return $this->superficie;
    }

    public function setSuperficie(?Superficie $superficie): self
    {
        $this->superficie = $superficie;

        return $this;
    }
}
