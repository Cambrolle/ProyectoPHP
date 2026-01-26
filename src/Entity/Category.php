<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'category', schema: 'competiciones')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 900)]
    private ?string $image = null;

    /**
     * @var Collection<int, Competicion>
     */

    #[JoinTable(name: 'users_groups')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ManyToMany(targetEntity: Group::class)]
    #[ORM\ManyToMany(targetEntity: Competicion::class)]
    private Collection $competiciones;

    public function __construct()
    {
        $this->competiciones = new ArrayCollection();
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
}
