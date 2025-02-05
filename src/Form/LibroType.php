<?php

namespace App\Form;

use App\Entity\Autor;
use App\Entity\Editorial;
use App\Entity\Libro;
use App\Entity\Socio;
use App\Repository\AutorRepository;
use App\Repository\EditorialRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LibroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo')
            ->add('anioPublicacion')
            ->add('paginas')
            ->add('isbn')
            ->add('precioCompra', MoneyType::class, [
                "divisor" => 100
            ])
            ->add('editorial', EntityType::class, [
                "label" => "Editoriales",
                "class" => Editorial::class,
                "choice_label" => "nombre",
                "query_builder" => function (EditorialRepository $editorialRepository) {
                    return $editorialRepository
                        ->createQueryBuilder("e")
                        ->select("e")
                        ->orderBy("e.nombre");
                }
            ])
            ->add('socio', EntityType::class, [
                "label" => "Socio Asociado: ",
                "class" => Socio::class,
                "choice_label" => function (Socio $socio) {
                    $tipoSocio = "";

                    if ($socio->getEsDocente()) $tipoSocio = "(Docente)";
                    if ($socio->getEsEstudiante()) $tipoSocio = "(Estudiante)";

                    return $socio->getNombre() . " " . $socio->getApellidos() . $tipoSocio;
                },
                "placeholder" => "No prestado :c",
                "required" => false
            ])
            ->add('autores', EntityType::class, [
                "label" => "Autores",
                "class" => Autor::class,
                "choice_label" => function (Autor $autor) {
                    return $autor->getNombre() . " " . $autor->getApellidos();
                },
                "query_builder" => function (AutorRepository $autorRepository) {
                    return $autorRepository
                        ->createQueryBuilder("a")
                        ->select("a")
                        ->orderBy("a.nombre")
                        ->addOrderBy("a.apellidos");
                },
                "multiple" => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Libro::class,
        ]);
    }
}
