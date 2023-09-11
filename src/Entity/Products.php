<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $quantity_left = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $product_pictures = [];

    #[ORM\OneToMany(mappedBy: 'product_id', targetEntity: ProductsPictures::class)]
    private Collection $productsPictures;

    public function __construct()
    {
        $this->productsPictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantityLeft(): ?int
    {
        return $this->quantity_left;
    }

    public function setQuantityLeft(int $quantity_left): static
    {
        $this->quantity_left = $quantity_left;

        return $this;
    }

    public function getProductPictures(): array
    {
        return $this->product_pictures;
    }

    public function setProductPictures(array $product_pictures): static
    {
        $this->product_pictures = $product_pictures;

        return $this;
    }

    /**
     * @return Collection<int, ProductsPictures>
     */
    public function getProductsPictures(): Collection
    {
        return $this->productsPictures;
    }

    public function addProductsPicture(ProductsPictures $productsPicture): static
    {
        if (!$this->productsPictures->contains($productsPicture)) {
            $this->productsPictures->add($productsPicture);
            $productsPicture->setProductId($this);
        }

        return $this;
    }

    public function removeProductsPicture(ProductsPictures $productsPicture): static
    {
        if ($this->productsPictures->removeElement($productsPicture)) {
            // set the owning side to null (unless already changed)
            if ($productsPicture->getProductId() === $this) {
                $productsPicture->setProductId(null);
            }
        }

        return $this;
    }
}
