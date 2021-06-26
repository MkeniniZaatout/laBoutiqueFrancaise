<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivraisonRepository::class)
 */
class Livraison
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="livraisons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $livreurName;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     */
    private $deliveryAddress;

    /**
     * @ORM\OneToMany(targetEntity=LivraisonDetails::class, mappedBy="livraison")
     */
    private $livraisonDetails;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPaid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeSessionId;

    public function __construct()
    {
        $this->livraisonDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTotal(): ?float 
    {
        $total = 0.0;
        $livraisonDetails = $this->getLivraisonDetails()->getValues();
        foreach ($livraisonDetails as $product) {
            $total = $total + $product->getTotal();
        }
        //dd($total);
        return $total*100;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLivreurName(): ?string
    {
        return $this->livreurName;
    }

    public function setLivreurName(string $livreurName): self
    {
        $this->livreurName = $livreurName;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(string $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * @return Collection|LivraisonDetails[]
     */
    public function getLivraisonDetails(): Collection
    {
        return $this->livraisonDetails;
    }

    public function addLivraisonDetail(LivraisonDetails $livraisonDetail): self
    {
        if (!$this->livraisonDetails->contains($livraisonDetail)) {
            $this->livraisonDetails[] = $livraisonDetail;
            $livraisonDetail->setLivraison($this);
        }

        return $this;
    }

    public function removeLivraisonDetail(LivraisonDetails $livraisonDetail): self
    {
        if ($this->livraisonDetails->removeElement($livraisonDetail)) {
            // set the owning side to null (unless already changed)
            if ($livraisonDetail->getLivraison() === $this) {
                $livraisonDetail->setLivraison(null);
            }
        }

        return $this;
    }

    public function getIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(?bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getStripeSessionId(): ?string
    {
        return $this->stripeSessionId;
    }

    public function setStripeSessionId(?string $stripeSessionId): self
    {
        $this->stripeSessionId = $stripeSessionId;

        return $this;
    }
}
