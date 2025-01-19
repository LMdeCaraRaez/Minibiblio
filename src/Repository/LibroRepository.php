<?php

namespace App\Repository;

use App\Entity\Libro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Libro>
 *
 * @method Libro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Libro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Libro[]    findAll()
 * @method Libro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Libro::class);
    }

    function listarLibros()
    {
        return $this->findAll();
    }

    function listarLibrosOrderByTitulo():array
    {
        return $this->getEntityManager()->createQuery("select l from App\\Entity\\Libro l order by l.titulo")->getResult();
    }
    function listarLibrosOrderByAnioDesc():array
    {
        return $this->getEntityManager()->createQuery("select l from App\\Entity\\Libro l order by l.anioPublicacion DESC")->getResult();
    }
    function listarLibrosPorContenerPalabra($palabra):array
    {
        return $this->getEntityManager()->createQuery("select l from App\\Entity\\Libro l where l.titulo like :titulo")->setParameter(":titulo", "%" . $palabra . "%")->getResult();
    }
    function listarLibrosSinAEnEditorial():array
    {
        return $this->getEntityManager()->createQuery("select l from App\\Entity\\Libro l join l.editorial e where e.nombre not like '%a%'")->getResult();
    }
    function listarLibrosConUnAutor():array
    {
        return $this->getEntityManager()->createQuery("select l from App\\Entity\\Libro l join l.autores a where size(l.autores) = 1")->getResult();
    }
}
