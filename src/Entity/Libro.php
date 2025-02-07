<?php

namespace App\Entity;

use App\Repository\LibroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LibroRepository::class)]
#[UniqueEntity(fields: "isbn", message: "Ya existe este isbn")]
class Libro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    private ?string $titulo = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $anioPublicacion = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $paginas = null;

    #[ORM\ManyToOne(targetEntity: Editorial::class, inversedBy: 'libros')]
    #[Assert\NotBlank]
    private ?Editorial $editorial;

    #[ORM\ManyToMany(targetEntity: Autor::class, inversedBy: 'libros')]
    #[Assert\Count(min: 1, minMessage: "Debe tener al menos un autor")]
    private Collection $autores;

    #[ORM\ManyToOne(targetEntity: Socio::class, inversedBy: 'libros')]
    private ?Socio $socio = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    #[Assert\Isbn(message: "El isbn debe ser valido, de tipo 10 o 13")]
    #[Assert\NotBlank]
    private ?string $isbn = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive(message: "El precio no puede ser negativo")]
    private ?int $precioCompra = null;

    public function __construct()
    {
        $this->autores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getAnioPublicacion(): ?\DateTimeImmutable
    {
        return $this->anioPublicacion;
    }

    public function setAnioPublicacion(\DateTimeImmutable $anioPublicacion): static
    {
        $this->anioPublicacion = $anioPublicacion;

        return $this;
    }

    public function getPaginas(): ?int
    {
        return $this->paginas;
    }

    public function setPaginas(int $paginas): static
    {
        $this->paginas = $paginas;

        return $this;
    }

    public function getEditorial(): ?Editorial
    {
        return $this->editorial;
    }

    public function setEditorial(?Editorial $editorial): static
    {
        $this->editorial = $editorial;

        return $this;
    }

    /**
     * @return Collection<int, Autor>
     */
    public function getAutores(): Collection
    {
        return $this->autores;
    }

    public function addAutor(Autor $autor): static
    {
        if (!$this->autores->contains($autor)) {
            $this->autores->add($autor);
        }

        return $this;
    }

    public function removeAutor(Autor $autor): static
    {
        $this->autores->removeElement($autor);

        return $this;
    }

    public function getSocio(): ?Socio
    {
        return $this->socio;
    }

    public function setSocio(?Socio $socio): static
    {
        $this->socio = $socio;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getPrecioCompra(): ?int
    {
        return $this->precioCompra;
    }

    public function setPrecioCompra(int $precioCompra): static
    {
        $this->precioCompra = $precioCompra;

        return $this;
    }
}
