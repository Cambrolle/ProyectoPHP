<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'category', schema: 'competiciones')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $image = null;

    /**
     * @var Collection<int, Competicion>
     */
    #[ORM\ManyToMany(targetEntity: Competicion::class, inversedBy: 'categories')]
    #[ORM\JoinTable(name: 'competiciones.category_competicion')] // Especificamos nombre y esquema
    #[ORM\JoinColumn(name: 'id_category', referencedColumnName: 'id')] // Tu id_category
    #[ORM\InverseJoinColumn(name: 'id_competicion', referencedColumnName: 'id')] // Tu id_competicion
    private Collection $competiciones;

    /**
     * @var Collection<int, Ranking>
     */
    #[ORM\OneToMany(targetEntity: Ranking::class, mappedBy: 'category')]
    private Collection $rankings;

    public function __construct()
    {
        $this->competiciones = new ArrayCollection();
        $this->rankings = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Competicion>
     */
    public function getCompeticiones(): Collection
    {
        return $this->competiciones;
    }

    public function addCompeticione(Competicion $competicione): static
    {
        if (!$this->competiciones->contains($competicione)) {
            $this->competiciones->add($competicione);
        }

        return $this;
    }

    public function removeCompeticione(Competicion $competicione): static
    {
        $this->competiciones->removeElement($competicione);

        return $this;
    }

    /**
     * @return Collection<int, Ranking>
     */
    public function getRankings(): Collection
    {
        return $this->rankings;
    }

    public function addRanking(Ranking $ranking): static
    {
        if (!$this->rankings->contains($ranking)) {
            $this->rankings->add($ranking);
            $ranking->setCategory($this);
        }

        return $this;
    }

    public function removeRanking(Ranking $ranking): static
    {
        if ($this->rankings->removeElement($ranking)) {
            // set the owning side to null (unless already changed)
            if ($ranking->getCategory() === $this) {
                $ranking->setCategory(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->name ?? 'Nueva Categoría';
    }
}
