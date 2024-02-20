<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\Array_;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private array $instructions = [];

    #[ORM\Column]
    private ?float $rating = null;

    #[ORM\Column]
    private ?array $rates = null;

    #[ORM\Column]
    private ?array $users_id_rated = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\Column]
    private array $ingredients = [];


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getInstructions(): array
    {
        return $this->instructions;
    }

    public function setInstructions(array $instructions): static
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function getRating(): ?float
    {
        $this->setRating();
        return $this->rating;
    }

    private function setRating(): static
    {
        if ($this->getNumberOfRates() == 0) {
            $this->rating = 0.0;
            return $this;
        }
        $this->rating = array_sum($this->getRates()) / count($this->getRates());

        return $this;
    }

    public function getNumberOfRates(): int
    {
        return $this->rates == null ? 0 : count($this->getRates());
    }

    private function getRates(): ?array
    {
        return $this->rates;
    }

    public function addRate(float $rate): static
    {
        $this->rates[] = $rate;

        return $this;
    }

    public function getUsersIdRated(): ?array
    {
        return $this->users_id_rated;
    }

    public function setUsersIdRated(int $users_id_rated): static
    {
        $this->users_id_rated[] = $users_id_rated;
        return $this;
    }

    public function checkIfUserRated(int $user_id): bool
    {
        return in_array($user_id, $this->getUsersIdRated());
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function setIngridients(array $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }
}
