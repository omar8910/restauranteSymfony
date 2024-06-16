<?php
// src/Controller/DefaultController.php

namespace App\Controller;

use App\Entity\Productos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/carrito')]
class CarritoController extends AbstractController
{

    #[Route(path: '/', name: 'app_carrito_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $productos = $this->getProductosFromCarrito($entityManager);
        // var_dump($productos); die();
        return $this->render('carrito/index.html.twig', [
            'carrito' => $productos,
        ]);
    }

    #[Route(path: '/add/{id}', name: 'app_carrito_add', methods: ['GET'])]
    public function addToCart($id, EntityManagerInterface $entityManager)
    {
        // guarda en una cookie los productos que se añaden al carrito
        $session = $this->requestStack->getSession();
        $carrito = $session->get('carrito', []);
        $carrito[$id] = ['id' => $id, 'unidades' => 1];
        $session->set('carrito', $carrito);
        $productos = $this->getProductosFromCarrito($entityManager);
        // var_dump($productos); die();
        return $this->render('carrito/index.html.twig', [
            'carrito' => $productos,
        ]);
    }

    #[Route(path: '/carrito/sum/{id}', name: 'app_carrito_sum', methods: ['GET'])]
    public function sumToCart($id, EntityManagerInterface $entityManager)
    {
        // guarda en una cookie los productos que se añaden al carrito
        $session = $this->requestStack->getSession();
        $carrito = $session->get('carrito', []);
        $carrito[$id]['unidades'] += 1;
        $session->set('carrito', $carrito);
        $productos = $this->getProductosFromCarrito($entityManager);
        // var_dump($productos); die();
        return $this->render('carrito/index.html.twig', [
            'carrito' => $productos,
        ]);
    }

    #[Route(path: '/carrito/res/{id}', name: 'app_carrito_res', methods: ['GET'])]
    public function resToCart($id, EntityManagerInterface $entityManager)
    {
        // guarda en una cookie los productos que se añaden al carrito
        $session = $this->requestStack->getSession();
        $carrito = $session->get('carrito', []);
        $carrito[$id]['unidades'] -= 1;
        if ($carrito[$id]['unidades'] == 0) {
            unset($carrito[$id]);
        }
        $session->set('carrito', $carrito);
        $productos = $this->getProductosFromCarrito($entityManager);
        // var_dump($productos); die();
        return $this->render('carrito/index.html.twig', [
            'carrito' => $productos,
        ]);
    }

    #[Route(path: '/carrito/delete/{id}', name: 'app_carrito_delete', methods: ['GET'])]
    public function deleteFromCart($id, EntityManagerInterface $entityManager)
    {
        // guarda en una cookie los productos que se añaden al carrito
        $session = $this->requestStack->getSession();
        $carrito = $session->get('carrito', []);
        unset($carrito[$id]);
        $session->set('carrito', $carrito);
        $productos = $this->getProductosFromCarrito($entityManager);
        // var_dump($productos); die();
        return $this->render('carrito/index.html.twig', [
            'carrito' => $productos,
        ]);
    }

    public function __construct(
        private RequestStack $requestStack,
    ) {
        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
    }

    public function getCarrito(): array
    {
        return $this->requestStack->getSession()->get('carrito', []);
    }

    public function getProductosFromCarrito($entityManager): array
    {
        $carrito = $this->getCarrito();
        $productos = [];
        foreach ($carrito as $id) {
            $productosRepository = $entityManager->getRepository(Productos::class);
            $producto = $productosRepository->find($id['id']);
            if ($producto) {
                $productos[] = ['id' => $id['id'], 'producto' => $producto, 'unidades' => $id['unidades'], 'productoNombre' => $producto->getNombre()];
            }
        }
        return $productos;
    }
}
