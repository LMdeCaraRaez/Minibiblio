<?php

namespace App\Entity;

use App\Repository\SocioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @method string getUserIdentifier()
 */
#[ORM\Entity(repositoryClass: SocioRepository::class)]
#[UniqueEntity(fields: "dni", message: "Ya existe alguien registrado con este dni")]
class Socio implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $dni = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $apellidos = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $nombre = null;

    #[ORM\OneToMany(targetEntity: Libro::class, mappedBy: 'socio')]
    private Collection $libros;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telefono = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $esDocente = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $esEstudiante = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    private ?bool $esAdministrador = null;

    #[ORM\Column(nullable: true)]
    private ?bool $esBibliotecario = null;

    public function __construct()
    {
        $this->libros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
    }
    public function getNombre(): ?string
    {
        return $this->nombre;
    }
    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }
    /**
     * @return Collection<int, Libro>
     */
    public function getLibros(): Collection
    {
        return $this->libros;
    }
    public function addLibro(Libro $libro): static
    {
        if (!$this->libros->contains($libro)) {
            $this->libros->add($libro);
            $libro->setSocio($this);
        }

        return $this;
    }
    public function removeLibro(Libro $libro): static
    {
        if ($this->libros->removeElement($libro)) {
            // set the owning side to null (unless already changed)
            if ($libro->getSocio() === $this) {
                $libro->setSocio(null);
            }
        }
        return $this;
    }
    public function getTelefono(): ?string
    {
        return $this->telefono;
    }
    public function setTelefono(?string $telefono): static
    {
        $this->telefono = $telefono;
        return $this;
    }

    public function getEsDocente(): ?string
    {
        return $this->esDocente;
    }

    public function setEsDocente(?string $esDocente): static
    {
        $this->esDocente = $esDocente;

        return $this;
    }

    public function getEsEstudiante(): ?string
    {
        return $this->esEstudiante;
    }

    public function setEsEstudiante(?string $esEstudiante): static
    {
        $this->esEstudiante = $esEstudiante;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }



    //importante, aunque de momento no se usen, esto no puede estar vacío
    public function getRoles(): array
    {
        $roles = [];
        $roles[] = "ROLE_USER";

        if ($this->esAdministrador) {
            $roles[] = "ROLE_ADMIN";
        }
        if ($this->esDocente) {
            $roles[] = "ROLE_DOCENTE";
        }
        if ($this->esBibliotecario) {
            $roles[] = "ROLE_BIBLIOTECARIO";
        }
        if ($this->esEstudiante) {
            $roles[] = "ROLE_ESTUDIANTE";
        }


        return array_unique($roles);
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

    public function isEsAdministrador(): ?bool
    {
        return $this->esAdministrador;
    }

    public function setEsAdministrador(?bool $esAdministrador): static
    {
        $this->esAdministrador = $esAdministrador;

        return $this;
    }

    public function isEsBibliotecario(): ?bool
    {
        return $this->esBibliotecario;
    }

    public function setEsBibliotecario(bool $esBibliotecario): static
    {
        $this->esBibliotecario = $esBibliotecario;

        return $this;
    }
}