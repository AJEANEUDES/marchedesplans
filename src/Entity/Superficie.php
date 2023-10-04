<?php

namespace App\Entity;

use App\Repository\SuperficieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SuperficieRepository::class)
 */
class Superficie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique="true")
     */
    private $nombredelots;

    
    public function __toString(){
       
        return $this->nombredelots;
        
    }

    /**
     * @ORM\OneToMany(targetEntity=Plans::class, mappedBy="superficie" , orphanRemoval=true )
     */
    private $plans;


    public function __construct()
    {
        $this->plans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombredelots(): ?string
    {
        return $this->nombredelots;
    }

    public function setNombredelots(string $nombredelots): self
    {
        $this->nombredelots = $nombredelots;

        return $this;
    }

    /**
     * @return Collection|plans[]
     */
    public function getPlans(): Collection
    {
        return $this->plans;
    }

    public function addPlan(plans $plan): self
    {
        if (!$this->plans->contains($plan)) {
            $this->plans[] = $plan;
            $plan->setSuperficie($this);
        }

        return $this;
    }

    public function removePlan(plans $plan): self
    {
        if ($this->plans->removeElement($plan)) {
            // set the owning side to null (unless already changed)
            if ($plan->getSuperficie() === $this) {
                $plan->setSuperficie(null);
            }
        }

        return $this;
    }
}
