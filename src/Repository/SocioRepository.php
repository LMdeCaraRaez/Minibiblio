<?php

namespace App\Repository;

use App\Entity\Socio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Socio>
 *
 * @method Socio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Socio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Socio[]    findAll()
 * @method Socio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Socio::class);
    }

    public function listarSociosOrderByname()
    {
        return $this->createQueryBuilder("s")
            ->select("s")
            ->orderBy("s.nombre")
            ->addOrderBy("s.apellidos")
            ->getQuery()
            ->getResult();
    }

    public function addSocio($socio): void
    {
        $this->getEntityManager()->persist($socio);
    }

    public function removeSocio($socio)
    {
        $this->getEntityManager()->remove($socio);
    }
    public function saveSocios()
    {
        $this->getEntityManager()->flush();
    }
}
