<?php

namespace App\Entity;

use App\Repository\ConsulterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsulterRepository::class)
 */
class Consulter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable('@'.strtotime('now'));

       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="plans")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Plans::class, inversedBy="miniplans ")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plans;
   
   

  

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

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

    
    public function getPlans(): ?plans
    {
        return $this->plans;
    }

    public function setPlans(?plans $plans): self
    {
        $this->plans = $plans;

        return $this;
    }


}
