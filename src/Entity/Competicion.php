<?php

namespace App\Entity;

use App\Repository\CompeticionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompeticionRepository::class)]
#[ORM\Table(name: 'competicion', schema: 'competiciones')]
class Competicion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column( name: "name" ,length: 255)]
    private ?string $name = null;

    #[ORM\Column( name: "type" ,length: 255)]
    private ?string $type = null;

    #[ORM\Column(name: "emblem", length: 800, nullable: true)] // Asegúrate de que diga nullable: true
    private ?string $emblem = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'competiciones')]
    private Collection $categories;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'competicion')]
    private Collection $reviews;

    /**
     * @var Collection<int, RankingCompeticion>
     */
    #[ORM\OneToMany(targetEntity: RankingCompeticion::class, mappedBy: 'competicion')]
    private Collection $rankingCompeticions;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->rankingCompeticions = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getEmblem(): ?string
    {
        return $this->emblem;
    }

    public function setEmblem(?string $emblem): static // El ? permite que sea NULL
    {
        $this->emblem = $emblem;

        return $this;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addCompeticione($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeCompeticione($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setCompeticion($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getCompeticion() === $this) {
                $review->setCompeticion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RankingCompeticion>
     */
    public function getRankingCompeticions(): Collection
    {
        return $this->rankingCompeticions;
    }

    public function addRankingCompeticion(RankingCompeticion $rankingCompeticion): static
    {
        if (!$this->rankingCompeticions->contains($rankingCompeticion)) {
            $this->rankingCompeticions->add($rankingCompeticion);
            $rankingCompeticion->setCompeticion($this);
        }

        return $this;
    }

    public function removeRankingCompeticion(RankingCompeticion $rankingCompeticion): static
    {
        if ($this->rankingCompeticions->removeElement($rankingCompeticion)) {
            // set the owning side to null (unless already changed)
            if ($rankingCompeticion->getCompeticion() === $this) {
                $rankingCompeticion->setCompeticion(null);
            }
        }

        return $this;
    }


}
