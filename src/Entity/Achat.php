<?php

namespace App\Entity;

use App\Repository\AchatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity(repositoryClass=AchatRepository::class)
 */

class Achat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Plans::class, inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plan;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="achat", orphanRemoval=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="achat")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $etat;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Miniplans::class, inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $miniplan;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $txreference;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $retrait;

   
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $demande;

    /**
     * @ORM\ManyToOne(targetEntity=Panier::class, inversedBy="achats")
     * @ORM\JoinColumn(nullable=true)
     */
    private $panier;

       /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statusmail;


    public function __toString(){
        // to show the name of the Category in the select
        return $this->id;
        // to show the id of the Category in the select
        // return $this->id;
    }

    
   
    


    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable('@'.strtotime('now'));

        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlan(): ?Plans
    {
        return $this->plan;
    }

    public function setPlan(?Plans $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getMiniplan(): ?Miniplans
    {
            return $this->miniplan;
    }

    public function setMiniplan(?Miniplans $miniplan): self
    {
        $this->miniplan = $miniplan;

        return $this;
    }

    public function getPayement(): ?string
    {
        return $this->payement;
    }

    public function setPayement(string $payement): self
    {
        $this->payement = $payement;

        return $this;
    }

    public function getTxreference(): ?int
    {
        return $this->txreference;
    }

    public function setTxreference(?int $txreference): self
    {
        $this->txreference = $txreference;

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

    public function getRetrait(): ?bool
    {
        return $this->retrait;
    }

    public function setRetrait(?bool $retrait): self
    {
        $this->retrait = $retrait;

        return $this;
    }

   

   

    public function getDemande(): ?string
    {
        return $this->demande;
    }

    public function setDemande(?string $demande): self
    {
        $this->demande = $demande;

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
        $this->panier = $panier;

        return $this;
    }



    public function getStatusMail(): ?bool
    {
        return $this->statusmail;
    }

    public function setStatusMail(?bool $statusmail): self
    {
        $this->statusmail = $statusmail;

        return $this;
    }

 
    
   

   

}

