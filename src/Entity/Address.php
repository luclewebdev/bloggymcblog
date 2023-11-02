<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $streetNumber = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $streetName = null;

    #[ORM\Column]
    private ?int $zipcode = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $city = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $profile = null;

    #[ORM\Column(nullable: true)]
    private ?bool $type = null;

    #[ORM\OneToMany(mappedBy: 'shippingAddress', targetEntity: Order::class)]
    private Collection $ordersAsShipping;

    #[ORM\OneToMany(mappedBy: 'billingAddress', targetEntity: Order::class)]
    private Collection $ordersAsBilling;

    public function __construct()
    {
        $this->ordersAsShipping = new ArrayCollection();
        $this->ordersAsBilling = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(int $streetNumber): static
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): static
    {
        $this->streetName = $streetName;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    public function isType(): ?bool
    {
        return $this->type;
    }

    public function setType(bool $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrdersAsShipping(): Collection
    {
        return $this->ordersAsShipping;
    }

    public function addOrdersAsShipping(Order $ordersAsShipping): static
    {
        if (!$this->ordersAsShipping->contains($ordersAsShipping)) {
            $this->ordersAsShipping->add($ordersAsShipping);
            $ordersAsShipping->setShippingAddress($this);
        }

        return $this;
    }

    public function removeOrdersAsShipping(Order $ordersAsShipping): static
    {
        if ($this->ordersAsShipping->removeElement($ordersAsShipping)) {
            // set the owning side to null (unless already changed)
            if ($ordersAsShipping->getShippingAddress() === $this) {
                $ordersAsShipping->setShippingAddress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrdersAsBilling(): Collection
    {
        return $this->ordersAsBilling;
    }

    public function addOrdersAsBilling(Order $ordersAsBilling): static
    {
        if (!$this->ordersAsBilling->contains($ordersAsBilling)) {
            $this->ordersAsBilling->add($ordersAsBilling);
            $ordersAsBilling->setBillingAddress($this);
        }

        return $this;
    }

    public function removeOrdersAsBilling(Order $ordersAsBilling): static
    {
        if ($this->ordersAsBilling->removeElement($ordersAsBilling)) {
            // set the owning side to null (unless already changed)
            if ($ordersAsBilling->getBillingAddress() === $this) {
                $ordersAsBilling->setBillingAddress(null);
            }
        }

        return $this;
    }
}
