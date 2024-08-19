<?php

namespace App\Entity;

use App\Repository\LogementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogementRepository::class)]
class Logement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $lot = null;

    #[ORM\Column(length: 255)]
    private ?string $rue = null;

    #[ORM\Column]
    private ?float $superficie = null;

    #[ORM\Column]
    private ?float $loyer = null;

    #[ORM\ManyToOne(inversedBy: 'logements')]
    private ?Quartier $quartier = null;

    #[ORM\ManyToOne(inversedBy: 'logements')]
    private ?TypeLogement $typeLog = null;

    #[ORM\OneToMany(mappedBy: 'logement', targetEntity: Individu::class)]
    private Collection $individus;

    public function __construct()
    {
        $this->individus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLot(): ?string
    {
        return $this->lot;
    }

    public function setLot(string $lot): static
    {
        $this->lot = $lot;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getSuperficie(): ?float
    {
        return $this->superficie;
    }

    public function setSuperficie(float $superficie): static
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getLoyer(): ?float
    {
        return $this->loyer;
    }

    public function setLoyer(float $loyer): static
    {
        $this->loyer = $loyer;

        return $this;
    }

    public function getQuartier(): ?Quartier
    {
        return $this->quartier;
    }

    public function setQuartier(?Quartier $quartier): static
    {
        $this->quartier = $quartier;

        return $this;
    }

    public function getTypeLog(): ?TypeLogement
    {
        return $this->typeLog;
    }

    public function setTypeLog(?TypeLogement $typeLog): static
    {
        $this->typeLog = $typeLog;

        return $this;
    }

    /**
     * @return Collection<int, Individu>
     */
    public function getIndividus(): Collection
    {
        return $this->individus;
    }

    public function addIndividu(Individu $individu): static
    {
        if (!$this->individus->contains($individu)) {
            $this->individus->add($individu);
            $individu->setLogement($this);
        }

        return $this;
    }

    public function removeIndividu(Individu $individu): static
    {
        if ($this->individus->removeElement($individu)) {
            // set the owning side to null (unless already changed)
            if ($individu->getLogement() === $this) {
                $individu->setLogement(null);
            }
        }

        return $this;
    }
}
