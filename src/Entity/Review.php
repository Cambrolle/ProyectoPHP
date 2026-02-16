<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ORM\Table(name: 'review', schema: 'competiciones')]
#[ORM\UniqueConstraint(name: 'unique_user_competicion', fields: ['usuario', 'competicion'])]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?int $stars = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Competicion $competicion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;
        return $this;
    }

    public function getStars(): ?int
    {
        return $this->stars;
    }

    public function setStars(int $stars): static
    {
        $this->stars = $stars;

        return $this;
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

    public function getCompeticion(): ?Competicion
    {
        return $this->competicion;
    }

    public function setCompeticion(?Competicion $competicion): static
    {
        $this->competicion = $competicion;

        return $this;
    }
}
