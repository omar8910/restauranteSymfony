<?php

namespace App\Controller;

use App\Entity\Pedidos;
use App\Entity\Productos;
use App\Form\PedidosType;
use App\Repository\PedidosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pedidos')]
class PedidosController extends AbstractController
{
    #[Route('/', name: 'app_pedidos_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $pedidos = $entityManager->getRepository(Pedidos::class)->findAll();
        $restaurantes = [];
        foreach ($pedidos as $pedido) {
            $restaurantes[$pedido->getId()] = $pedido->getRestaurante()->getNombre();
        }
        return $this->render('pedidos/index.html.twig', [
            'pedidos' => $pedidos,
            'restaurantes' => $restaurantes,
        ]);
    }

    #[Route('/new', name: 'app_pedidos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pedido = new Pedidos();
        $form = $this->createForm(PedidosType::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pedido);
            $entityManager->flush();

            $carrito = $this->requestStack->getSession()->get('carrito', []);
            $this->saveCarritoToDatabase($carrito, $entityManager, $pedido);

            return $this->redirectToRoute('app_pedidos_index', [], Response::HTTP_SEE_OTHER);
        }

        $productosRepository = $entityManager->getRepository(Productos::class);
        $productos = $productosRepository->findAll();
        return $this->render('pedidos/new.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
            'productos' => $productos,
        ]);
    }

    #[Route('/{id}', name: 'app_pedidos_show', methods: ['GET'])]
    public function show(Pedidos $pedido, EntityManagerInterface $entityManager): Response
    {
        $productos = $this->getProductos($entityManager, $pedido);
        return $this->render('pedidos/show.html.twig', [
            'pedido' => $pedido,
            'productos' => $productos,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pedidos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pedidos $pedido, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PedidosType::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pedidos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pedidos/edit.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pedidos_delete', methods: ['POST'])]
    public function delete(Request $request, Pedidos $pedido, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pedido->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pedido);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pedidos_index', [], Response::HTTP_SEE_OTHER);
    }

    public function __construct(
        private RequestStack $requestStack,
    ) {
        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
    }

    public function saveCarritoToDatabase(array $carrito, EntityManagerInterface $entityManager, Pedidos $pedido): void
    {
        // ejecuta una query normal y corriente
        $conn = $entityManager->getConnection();
        foreach ($carrito as $id => $producto) {
            $conn->executeStatement('INSERT INTO pedidos_productos (pedidos_id, productos_id, unidades) VALUES (:pedido_id, :producto_id, :unidades)', [
                'pedido_id' => $pedido->getId(),
                'producto_id' => $producto['id'],
                'unidades' => $producto['unidades'],
            ]);
        }
        $this->requestStack->getSession()->set('carrito', []); // vacía el carrito
    }

    public function getProductos(EntityManagerInterface $entityManager, Pedidos $pedido) {
        $conn = $entityManager->getConnection();
        $sql = 'SELECT p.id, p.nombre, pp.unidades FROM productos p JOIN pedidos_productos pp ON p.id = pp.productos_id WHERE pp.pedidos_id = :pedido_id';
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['pedido_id' => $pedido->getId()]);
        return $result->fetchAllAssociative();
    }

}
