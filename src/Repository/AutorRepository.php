<?php

namespace App\Repository;

use App\Entity\Autor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Autor>
 *
 * @method Autor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Autor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Autor[]    findAll()
 * @method Autor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Autor::class);
    }

    public function listarAutores()
    {
        return $this->findAll();
    }

    public function listarAutoresOrderByEdad(): array
    {
        return $this->getEntityManager()->createQuery("select a, size(a.libros) as nLibros from App\\Entity\\Autor a order by a.fechaNacimiento")->getResult();
    }

    public function listarAutoresPorLibroId($id)
    {
        return $this->getEntityManager()->createQuery("select a from App\\Entity\\Autor a where a.id = :id")->setParameter("id", $id)->getResult();
    }

}
