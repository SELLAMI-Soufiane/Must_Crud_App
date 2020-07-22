<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @SWG\Property(type="integer", property="id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(type="string", property="name")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @SWG\Property(type="string", property="url")
     */
    private $url;

    /**
     * @ORM\Column(type="text")
     * @SWG\Property(type="string", property="description")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @SWG\Property(type="boolean", property="active"),
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brand;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="products")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getBrand(): ?array
    {
        $brand = $this->brand;
        return array(
            'id' => $brand->getId(),
            'name' => $brand->getName(),
        );
    }

    public function setBrand(?brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return array|category[]
     */
    public function getCategories(): array
    {
        $categoriesArray = [];
        foreach ($this->categories as $category){
            $categoriesArray[] = array(
                'id' => $category->getId(),
                'name' => $category->getName(),
            );
        }
        return $categoriesArray;
    }

    public function addCategory(category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }
}
