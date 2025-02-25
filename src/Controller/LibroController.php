<?php

namespace App\Controller;

use App\Entity\Libro;
use App\Entity\Socio;
use App\Form\ChangeUserPasswordType;
use App\Form\LibroType;
use App\Form\SocioType;
use App\Repository\AutorRepository;
use App\Repository\LibroRepository;
use App\Repository\SocioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LibroController extends AbstractController
{
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

    #[IsGranted("ROLE_USER")]
    #[Route("/libro/listar", name: "libro_listar")]
    function listarLibros(LibroRepository $libroRepository): Response
    {
        $libros = $libroRepository->listarLibros();

        return $this->render("libros_listar.html.twig", ["libros" => $libros]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route("/libro/autores/{id}", name: "libro_autores")]
    function listarAutores(Libro $libro): Response
    {
        $autores = $libro->getAutores();

        return $this->render("autores_listar.html.twig", ["autores" => $autores]);
    }


    #[IsGranted("ROLE_ADMIN")]
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

    #[IsGranted("ROLE_ADMIN")]
    #[Route("/libro_nuevo", name: "libro_nuevo")]
    function libroNuevo(Request $request, LibroRepository $libroRepository): Response
    {
        $libro = new Libro();
        $libroRepository->saveLibros();
        return $this->modificarLibro($libro, $libroRepository, $request);
    }


    #[IsGranted("ROLE_BIBLIOTECARIO")]
    #[Route("/socio/listar", name: "listar_socios")]
    function socioListar(SocioRepository $socioRepository): Response
    {

        $socios = $socioRepository->listarSociosOrderByname();
        return $this->render("socios_listar.html.twig", ["socios" => $socios]);
    }

    #[IsGranted("ROLE_DOCENTE")]
    #[Route("/libro_modificar/{id}", name: "libro_modificar")]
    function modificarLibro(Libro $libro, LibroRepository $libroRepository, Request $request): Response
    {
        $nuevo = $libro->getId() === null;

        if ($this->isGranted("ROLE_BIBLIOTECARIO")) {
            $form = $this->createForm(LibroType::class, $libro, ["bibliotecario" => true]);
        } else {
            $form = $this->createForm(LibroType::class, $libro, ["bibliotecario" => false]);
        }
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

    #[IsGranted("ROLE_USER")]
    #[Route("/socio/modificar/{id}", name: "socio_modificar")]
    function modificarSocio(Socio $socio, SocioRepository $socioRepository, Request $request): Response
    {
        $nuevoSocio = $socio->getId() === null;
        $form = $this->createForm(SocioType::class, $socio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($nuevoSocio) {
                $socioRepository->addSocio($socio);
                $this->addFlash("success", "Se ha creado el socio con éxito");
            } else {
                $this->addFlash("success", "Se ha modificado el socio con éxito");
            }
            $socioRepository->saveSocios();

            return $this->redirectToRoute("listar_socios");
        }

        return $this->render("modificar_socios.html.twig", ["form" => $form->createView(), "socio" => $socio]);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route("/socio/nuevo", name: "socio_nuevo")]
    function crearSocio(SocioRepository $socioRepository, Request $request): Response
    {
        $socio = new Socio();
        $socioRepository->saveSocios();
        return $this->modificarSocio($socio, $socioRepository, $request);
    }

    #[IsGranted("ROLE_USER")]
    #[Route("socio/borrar/{id}", name: "socio_eliminar")]
    function eliminarSocio(Socio $socio, SocioRepository $socioRepository, Request $request): Response
    {
        if ($request->request->has("confirmar")) {
            try {
                $socioRepository->removeSocio($socio);
                $socioRepository->saveSocios();
                return $this->redirectToRoute("listar_socios");
            } catch (\Exception $e) {
                $this->addFlash("error", "No se ha podido eliminar el socio");
            }
        }
        return $this->render("socio_eliminar.html.twig", ["socio" => $socio]);
    }


    //Login y logout no necesitan ningun tipo de validacion
    #[Route("/login", name: "app_login")]
    function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("security/login.html.twig", [
            "last_username" => $lastUsername,
            "error" => $error,
        ]);
    }

    #[Route("/logout", name: "app_logout")]
    function logout()
    {
        throw new \LogicException("Esto no deberia ejecutarse nunca");
    }

    #[Route("/", name: "portada")]
    function portada(): Response
    {
        return $this->render("portada.html.twig");
    }

    #[IsGranted("ROLE_USER")]
    #[Route("/clave")]
    function cambiarClavePropia(Request $request, UserPasswordHasherInterface $passwordEncoder, SocioRepository $socioRepository): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangeUserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->hashPassword($user, $form->get("newPassword")->getData())
            );
            $socioRepository->saveSocios();
            $this->addFlash("success", "Se ha actualizado tu contraseña correctamente");

            return $this->redirectToRoute("portada");
        }

        return $this->render("security/change_password.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route("/clave/{id}", name: "cambiar_clave_usuario")]
    function cambiarClaveUsuario(Request $request, UserPasswordHasherInterface $passwordEncoder, SocioRepository $socioRepository, Socio $user): Response
    {

        $form = $this->createForm(ChangeUserPasswordType::class, $user, [
            "admin" => $user !== $this->getUser() // comprobamos que no nos cambiemos a nosotros mismos la contra
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->hashPassword($user, $form->get("newPassword")->getData())
            );
            $socioRepository->saveSocios();
            $this->addFlash("success", "Se ha actualizado tu contraseña correctamente");

            return $this->redirectToRoute("portada");
        }

        return $this->render("security/change_password.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}