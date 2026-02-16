<?php

namespace App\Entity;

use App\Repository\RankingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RankingRepository::class)]
#[ORM\Table(name: 'ranking', schema: 'competiciones')]
#[ORM\UniqueConstraint(name: 'unique_user_category_ranking', fields: ['usuario', 'category'])]
class Ranking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rankings')]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(inversedBy: 'rankings')]
    private ?Category $category = null;

    /**
     * @var Collection<int, RankingCompeticion>
     */
    #[ORM\OneToMany(
        targetEntity: RankingCompeticion::class,
        mappedBy: 'ranking',
        cascade: ['remove'],
        orphanRemoval: true
    )]
    private Collection $rankingCompeticions;

    public function __construct()
    {
        $this->rankingCompeticions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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
            $rankingCompeticion->setRanking($this);
        }

        return $this;
    }

    public function removeRankingCompeticion(RankingCompeticion $rankingCompeticion): static
    {
        if ($this->rankingCompeticions->removeElement($rankingCompeticion)) {
            // set the owning side to null (unless already changed)
            if ($rankingCompeticion->getRanking() === $this) {
                $rankingCompeticion->setRanking(null);
            }
        }

        return $this;
    }
}
