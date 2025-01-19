<?php

namespace App\Controller;

use App\Entity\Autor;
use App\Entity\Libro;
use App\Repository\LibroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibroController extends AbstractController
{
    #[Route("/libro/listar", name: "libro_listar")]
    function listarLibros(LibroRepository $libroRepository)
    {
        $libros = $libroRepository->listarLibros();
        dump($libros);

        return $this->render("libros_listar.html.twig", ["libros" => $libros]);
    }

    #[Route("/libro/autores/{id}", name: "libro_autores")]
    function listarAutores(Libro $libro): Response
    {
        $autores = $libro->getAutores();
        dump($autores);

        return $this->render("autores_listar.html.twig", ["autores" => $autores]);
    }

    #[Route("/ap1", name: "ap1")]
    function ap1(LibroRepository $libroRepository): Response
    {
        $libros = $libroRepository->listarLibrosOrderByTitulo();
        return $this->render("ap1.html.twig", ["libros" => $libros]);
    }
}