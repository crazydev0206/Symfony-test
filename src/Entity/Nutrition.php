<?php

namespace App\Entity;

use App\Repository\NutritionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NutritionRepository::class)]
class Nutrition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'float')]
    private $carbohydrates;

    #[ORM\Column(type: 'float')]
    private $protein;

    #[ORM\Column(type: 'float')]
    private $fat;

    #[ORM\Column(type: 'float')]
    private $calories;

    #[ORM\Column(type: 'float')]
    private $sugar;

    #[ORM\OneToOne(targetEntity : "Fruit", inversedBy : "nutrition", cascade:["persist"])]
    #[ORM\JoinColumn(name : "fruit_id", referencedColumnName : "id")]
    private $fruit;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarbohydrates(): ?float
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(float $carbohydrates): self
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getProtein(): ?float
    {
        return $this->protein;
    }

    public function setProtein(float $protein): self
    {
        $this->protein = $protein;

        return $this;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function setFat(float $fat): self
    {
        $this->fat = $fat;

        return $this;
    }

    public function getCalories(): ?float
    {
        return $this->calories;
    }

    public function setCalories(float $calories): self
    {
        $this->calories = $calories;

        return $this;
    }

    public function getSugar(): ?float
    {
        return $this->sugar;
    }

    public function setSugar(float $sugar): self
    {
        $this->sugar = $sugar;

        return $this;
    }

    public function getFruit(): ?Fruit
    {
        return $this->fruit;
    }

    public function setFruit(?Fruit $fruit): self
    {
        $this->fruit = $fruit;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s (Carbs: %.2f, Protein: %.2f, Fat: %.2f, Calories: %.2f, Sugar: %.2f)',
            $this->getId(),
            $this->getCarbohydrates(),
            $this->getProtein(),
            $this->getFat(),
            $this->getCalories(),
            $this->getSugar()
        );
    }

}
