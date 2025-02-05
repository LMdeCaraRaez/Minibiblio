<?php

namespace App\Controller;

use App\Entity\Libro;
use App\Form\LibroType;
use App\Repository\AutorRepository;
use App\Repository\LibroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibroController extends AbstractController
{
    #[Route("/libro/listar", name: "libro_listar")]
    function listarLibros(LibroRepository $libroRepository)
    {
        $libros = $libroRepository->listarLibros();

        return $this->render("libros_listar.html.twig", ["libros" => $libros]);
    }

    #[Route("/libro/autores/{id}", name: "libro_autores")]
    function listarAutores(Libro $libro): Response
    {
        $autores = $libro->getAutores();

        return $this->render("autores_listar.html.twig", ["autores" => $autores]);
    }

    #[Route("/ap1", name: "ap1")]
    function ap1(LibroRepository $libroRepository): Response
    {
        $libros = $libroRepository->listarLibrosOrderByTitulo();
        return $this->render("ap1.html.twig", ["libros" => $libros]);
    }

    #[Route("/ap2", name: "ap2")]
    function ap2(LibroRepository $libroRepository): Response
    {
        $libros = $libroRepository->listarLibrosOrderByAnioDesc();
        return $this->render("ap2.html.twig", ["libros" => $libros]);
    }

    #[Route("/ap3", name: "ap3")]
    function ap3(LibroRepository $libroRepository): Response
    {
        $libros = $libroRepository->listarLibrosPorContenerPalabra("tormentas");
        return $this->render("ap1.html.twig", ["libros" => $libros]);
    }

    #[Route("/ap4", name: "ap4")]
    function ap4(LibroRepository $libroRepository): Response
    {
        $libros = $libroRepository->listarLibrosSinAEnEditorial();
        return $this->render("ap4.html.twig", ["libros" => $libros]);
    }

    #[Route("/ap5", name: "ap5")]
    function ap5(LibroRepository $libroRepository): Response
    {
        $libros = $libroRepository->listarLibrosConUnAutor();

        return $this->render("ap5.html.twig", ["libros" => $libros]);
    }

    #[Route("/ap6", name: "ap6")]
    function ap6(AutorRepository $autorRepository): Response
    {
        $datos = $autorRepository->listarAutoresOrderByEdad();
        return $this->render("ap6.html.twig", ["datos" => $datos]);
    }

    #[Route("/ap7", name: "ap7")]
    function ap7(LibroRepository $libroRepository): Response
    {
        $libros = $libroRepository->listarLibrosConAutoresOrderByTitulo();
        dump($libros);
        return $this->render("ap7.html.twig", ["libros" => $libros]);
    }

    #[Route("/ap8", name: "ap8")]
    function ap8(LibroRepository $libroRepository): Response
    {
        $libros = $libroRepository->listarLibrosOrderByTitulo();
        dump($libros);
        return $this->render("ap8.html.twig", ["libros" => $libros]);
    }

    #[Route("/ap8/{libroId}", name: "ap8_autores")]
    function ap8_2(AutorRepository $autorRepository, $libroId): Response
    {
        $autores = $autorRepository->listarAutoresPorLibroId($libroId);
        dump($autores);
        return $this->render("ap8_2.html.twig");
    }

    #[Route("/libro_modificar/{id}", name: "libro_modificar")]
    function modificarLibro(Libro $libro, LibroRepository $libroRepository, Request $request): Response
    {
        $nuevo = $libro->getId() === null;

        $form = $this->createForm(LibroType::class, $libro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($nuevo) {
                $libroRepository->addLibro($libro);
            }

            $libroRepository->saveLibros();
            if ($nuevo) {
                $this->addFlash("success", "Vehiculo creado con exito");
            } else {
                $this->addFlash("success", "Vehiculo editado con exito");
            }
            // Importante el return sino no funciona
            return $this->redirectToRoute("libro_listar");
        }

        return $this->render("modificar_libro.html.twig", ["form" => $form->createView(), "libro" => $libro]);
    }

    #[Route("libro/eliminar/{id}", name: "libro_eliminar")]
    function eliminarLibro(Libro $libro, Request $request, LibroRepository $libroRepository): Response
    {
        if ($request->request->has("confirmar")) {
            try {
                $libroRepository->remove($libro);
                $libroRepository->saveLibros();
                return $this->redirectToRoute("libro_listar");
            } catch (\Exception $e) {
                $this->addFlash("error", "No se ha podido eliminar el libro");
            }
        }

        return $this->render("libro_eliminar.html.twig", ["libro" => $libro]);
    }

    #[Route("/libro_nuevo", name: "libro_nuevo")]
    function libroNuevo(Request $request, LibroRepository $libroRepository): Response
    {
        $libro = new Libro();
        $libroRepository->saveLibros();
        return $this->modificarLibro($libro, $libroRepository, $request);
    }
}