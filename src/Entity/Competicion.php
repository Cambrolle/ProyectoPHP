<?php

namespace App\Entity;

use App\Repository\CompeticionRepository;
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

    #[ORM\Column( name: "emblem" ,length: 800)]
    private ?string $emblem = null;

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

    public function setEmblem(string $emblem): static
    {
        $this->emblem = $emblem;

        return $this;
    }
}
