<?php

namespace App\Entity;

use App\Repository\RankingCompeticionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RankingCompeticionRepository::class)]
#[ORM\Table(name: 'ranking_competicion', schema: 'competiciones')]
class RankingCompeticion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\ManyToOne(inversedBy: 'rankingCompeticions')]
    private ?Competicion $competicion = null;

    #[ORM\ManyToOne(inversedBy: 'rankingCompeticions')]
    private ?Ranking $ranking = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getCompeticion(): ?Competicion
    {
        return $this->competicion;
    }

    public function setCompeticion(?Competicion $competicion): static
    {
        $this->competicion = $competicion;

        return $this;
    }

    public function getRanking(): ?Ranking
    {
        return $this->ranking;
    }

    public function setRanking(?Ranking $ranking): static
    {
        $this->ranking = $ranking;

        return $this;
    }
}
