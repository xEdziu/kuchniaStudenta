<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $calories_per_100gram = null;

    #[ORM\Column]
    private ?float $protein_per_100gram = null;

    #[ORM\Column]
    private ?float $carbohydrates_per_100gram = null;

    #[ORM\Column]
    private ?float $fat_per_100gram = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $measure_type = null;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\ManyToMany(targetEntity: Store::class, inversedBy: 'ingredients')]
    private Collection $store_id;

    public function __construct()
    {
        $this->store_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCaloriesPer100gram(): ?float
    {
        return $this->calories_per_100gram;
    }

    public function setCaloriesPer100gram(float $calories_per_100gram): static
    {
        $this->calories_per_100gram = $calories_per_100gram;

        return $this;
    }

    public function getProteinPer100gram(): ?float
    {
        return $this->protein_per_100gram;
    }

    public function setProteinPer100gram(float $protein_per_100gram): static
    {
        $this->protein_per_100gram = $protein_per_100gram;

        return $this;
    }

    public function getCarbohydratesPer100gram(): ?float
    {
        return $this->carbohydrates_per_100gram;
    }

    public function setCarbohydratesPer100gram(float $carbohydrates_per_100gram): static
    {
        $this->carbohydrates_per_100gram = $carbohydrates_per_100gram;

        return $this;
    }

    public function getFatPer100gram(): ?float
    {
        return $this->fat_per_100gram;
    }

    public function setFatPer100gram(float $fat_per_100gram): static
    {
        $this->fat_per_100gram = $fat_per_100gram;

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

    public function getMeasureType(): ?string
    {
        return $this->measure_type;
    }

    public function setMeasureType(string $measure_type): static
    {
        $this->measure_type = $measure_type;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Store>
     */
    public function getStoreId(): Collection
    {
        return $this->store_id;
    }

    public function addStoreId(Store $storeId): static
    {
        if (!$this->store_id->contains($storeId)) {
            $this->store_id->add($storeId);
        }

        return $this;
    }

    public function removeStoreId(Store $storeId): static
    {
        $this->store_id->removeElement($storeId);

        return $this;
    }

    public function clearStoresIds(): static
    {
        $this->store_id->clear();

        return $this;
    }

    public function toArray(): array
    {
        $stores = [];
        foreach ($this->getStoreId() as $store) {
            $stores[] = $store;
        }
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'calories_per_100gram' => $this->getCaloriesPer100gram(),
            'protein_per_100gram' => $this->getProteinPer100gram(),
            'carbohydrates_per_100gram' => $this->getCarbohydratesPer100gram(),
            'fat_per_100gram' => $this->getFatPer100gram(),
            'price' => $this->getPrice(),
            'measure_type' => $this->getMeasureType(),
            'quantity' => $this->getQuantity(),
            'label' => $this->getLabel(),
            'store_id' => $stores
        ];
    }
}
