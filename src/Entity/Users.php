<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Users implements UserInterface
{
    

    private $plainPassword;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    // /**
    //  * @ORM\Column(type="string", length=12)
    //  */
    // private $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     *pseudo=nom
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenoms;

    /**
     * @ORM\ManyToOne(targetEntity=Pays::class, inversedBy="users")
     */
    private $pays;

   

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\OneToMany(targetEntity=Achat::class, mappedBy="users", orphanRemoval=true)
     */
    private $achat;

    /**
     * @ORM\OneToMany(targetEntity=Plans::class, mappedBy="user", orphanRemoval=true)
     */
    private $plans;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $banque;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender", orphanRemoval=true)
     */
    private $sent;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="recipient", orphanRemoval=true)
     */
    private $received;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_verified;

    /**
     * @ORM\OneToMany(targetEntity=Miniplans::class, mappedBy="user", orphanRemoval=true)
     */
    private $miniplans;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity=Withdrawal::class, mappedBy="user")
     */
    private $withdrawals;

    /**
     * @ORM\OneToMany(targetEntity=Panier::class, mappedBy="user", orphanRemoval=true)
     */
    private $paniers;

    
    
   

    public function __construct()
    {
        $this->achat = new ArrayCollection();
        $this->plans = new ArrayCollection();
        $this->sent = new ArrayCollection();
        $this->received = new ArrayCollection();
        $this->miniplans = new ArrayCollection();
        $this->withdrawals = new ArrayCollection();
        $this->paniers = new ArrayCollection();
    }

    
   
    public function __toString(){
        // to show the name of the Category in the select
        return $this->pseudo;
        // to show the name of the Category in the select
        return $this->prenoms;
        // to show the id of the Category in the select
        // return $this->id;
    }
  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    
    // public function getRoles(): ?string
    // {
    //     $roles = "R3";
    //     $roles = $this->roles;        

    //     if($roles == "R1")
    //         return['ROLE_ADMIN'];
    //     else if ($roles == "R2")
    //         return['ROLE_ARCHITECTE'];
    //     else if ($roles == "R3")
    //         return['ROLE_USER'];

    //     // guarantee every user at least has ROLE_USER
    //     // $roles[] = 'ROLE_USER';

    //     // return array_unique($roles);

    //     return $roles;
    // }

    // public function setRoles(string $roles): self
    // {
    //     $this->roles = $roles;


    //     return $this;
    // }

    public function getRoles()
    {
        $roles = "R3";
        $roles = $this->roles;
        

        if($roles=="R1")
            return['ROLE_ADMIN'];
        else if ($roles=="R2")
            return['ROLE_ARCHITECTE'];
        else if ($roles=="R3")
            return['ROLE_USER'];
        else if($roles == "R0")
            return['ROLE_SUPER_ADMIN'];

        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        // return array_unique($roles);

        return $roles;
    }



    public function setRoles($roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

   

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(string $prenoms): self
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    
    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    /**
     * @return Collection|Achat[]
     */
    public function getAchat(): Collection
    {
        return $this->achat;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achat->contains($achat))
         {
            $this->achat[] = $achat;
            $achat->setUsers($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achat->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getUsers() === $this) {
                $achat->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Plans[]
     */
    public function getPlans(): Collection
    {
        return $this->plans;
    }

    public function addPlan(Plans $plan): self
    {
        if (!$this->plans->contains($plan)) {
            $this->plans[] = $plan;
            $plan->setUser($this);
        }

        return $this;
    }

    public function removePlan(Plans $plan): self
    {
        if ($this->plans->removeElement($plan)) {
            // set the owning side to null (unless already changed)
            if ($plan->getUser() === $this) {
                $plan->setUser(null);
            }
        }

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
    
    public function getBanque(): ?string
    {
        return $this->banque;
    }

    public function setBanque(?string $banque): self
    {
        $this->banque = $banque;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getSent(): Collection
    {
        return $this->sent;
    }

    public function addSent(Message $sent): self
    {
        if (!$this->sent->contains($sent)) {
            $this->sent[] = $sent;
            $sent->setSender($this);
        }

        return $this;
    }

    public function removeSent(Message $sent): self
    {
        if ($this->sent->removeElement($sent)) {
            // set the owning side to null (unless already changed)
            if ($sent->getSender() === $this) {
                $sent->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getReceived(): Collection
    {
        return $this->received;
    }

    public function addReceived(Message $received): self
    {
        if (!$this->received->contains($received)) {
            $this->received[] = $received;
            $received->setRecipient($this);
        }

        return $this;
    }

    public function removeReceived(Message $received): self
    {
        if ($this->received->removeElement($received)) {
            // set the owning side to null (unless already changed)
            if ($received->getRecipient() === $this) {
                $received->setRecipient(null);
            }
        }

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(?int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }



    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(?bool $is_verified): self
    {
        $this->is_verified = $is_verified;

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
            $miniplan->setUser($this);
        }

        return $this;
    }

    public function removeMiniplan(Miniplans $miniplan): self
    {
        if ($this->miniplans->removeElement($miniplan)) {
            // set the owning side to null (unless already changed)
            if ($miniplan->getUser() === $this) {
                $miniplan->setUser(null);
            }
        }

        return $this;
    }

    // /**
    //  * @see UserInterface
    // */

    // public function getRoles(): ?string
    // {
    //     $roles = "R3";
    //     $roles = $this->roles;        

    //     if($roles == "R1")
    //         return['ROLE_ADMIN'];

    //     else if ($roles == "R2")
    //         return['ROLE_ARCHITECTE'];
    //     else if ($roles == "R3")
    //         return['ROLE_USER'];

    //     // guarantee every user at least has ROLE_USER
    //     // $roles[] = 'ROLE_USER';

    //     // return array_unique($roles);

       
    //     return $this->roles;
    // }

    // public function setRoles(string $roles): self
    // {
    //     $this->roles = $roles;

    //     return $this;
    // }

    /**
     * @return Collection|Withdrawal[]
     */
    public function getWithdrawals(): Collection
    {
        return $this->withdrawals;
    }

    public function addWithdrawal(Withdrawal $withdrawal): self
    {
        if (!$this->withdrawals->contains($withdrawal)) {
            $this->withdrawals[] = $withdrawal;
            $withdrawal->setUser($this);
        }

        return $this;
    }

    public function removeWithdrawal(Withdrawal $withdrawal): self
    {
        if ($this->withdrawals->removeElement($withdrawal)) {
            // set the owning side to null (unless already changed)
            if ($withdrawal->getUser() === $this) {
                $withdrawal->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Panier[]
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->setUser($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getUser() === $this) {
                $panier->setUser(null);
            }
        }

        return $this;
    }

    

  
    
   
}
