<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $name;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 10,
     *      max = 1000,
     *      minMessage = "Product description must be at least {{ limit }} characters long",
     *      maxMessage = "Product description cannot be longer than {{ limit }} characters")
     */
    #[ORM\Column(type: 'text')]
    private $description;

    /**
     * @Assert\Type(type="double", message="The product price {{ value }} is not a valid {{ type }}")
     * @Assert\NotBlank(message="Product price cannot be empty")
     */
    #[ORM\Column(type: 'decimal', precision: 50, scale: 2)]
    private $price;

    #[ORM\Column(type: 'string', length: 6)]
    #[Assert\Currency]
    #[Assert\NotBlank]
    private $currency;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
