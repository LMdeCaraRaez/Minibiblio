<?php

namespace App\DataFixtures;

use App\Entity\Socio;
use App\Factory\AutorFactory;
use App\Factory\EditorialFactory;
use App\Factory\LibroFactory;
use App\Factory\SocioFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function Zenstruck\Foundry\faker;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->passwordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        SocioFactory::createOne(
            [
                "email" => "admin@biblio.local",
                "password" => $this->passwordHasher->hashPassword(
                    new Socio(),
                    "admin"
                ),
                "esAdministrador" => true
            ]
        );
        SocioFactory::createOne(
            [
                "email" => "docente@biblio.local",
                "password" => $this->passwordHasher->hashPassword(
                    new Socio(),
                    "docente"
                ),
                "esAdministrador" => true
            ]
        );
        SocioFactory::createOne(
            [
                "email" => "estudiante@biblio.local",
                "password" => $this->passwordHasher->hashPassword(
                    new Socio(),
                    "estudiante"
                ),
                "esAdministrador" => true
            ]
        );
        SocioFactory::createMany(20, function () {
            $esDocente = SocioFactory::faker()->boolean(10);

            return [
                "email" => SocioFactory::faker()->email,
                "password" => $this->passwordHasher->hashPassword(
                    new Socio(),
                    "prueba"
                ),
                "esAdministrador" => false,
                "esDocente" => $esDocente,
                "esEstudiante" => !$esDocente
            ];
        });

        EditorialFactory::createMany(100);
        AutorFactory::createMany(200);
//        SocioFactory::createMany(20);
        LibroFactory::createMany(50, function () {
            return [
                "autores" => AutorFactory::randomRange(1, 3),
                "socio" => SocioFactory::faker()->boolean(25) ? SocioFactory::random() : null,
                "editorial" => EditorialFactory::random()
            ];
        });

        $manager->flush();
    }
}
