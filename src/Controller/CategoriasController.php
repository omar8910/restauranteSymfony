<?php

namespace App\Controller;

use App\Entity\Categorias;
use App\Form\CategoriasType;
use App\Repository\CategoriasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categorias')]
class CategoriasController extends AbstractController
{
    #[Route('/', name: 'app_categorias_index', methods: ['GET'])]
    public function index(CategoriasRepository $categoriasRepository): Response
    {
        return $this->render('categorias/index.html.twig', [
            'categorias' => $categoriasRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorias_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoria = new Categorias();
        $form = $this->createForm(CategoriasType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categoria);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorias_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorias/new.html.twig', [
            'categoria' => $categoria,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorias_show', methods: ['GET'])]
    public function show(Categorias $categoria): Response
    {
        return $this->render('categorias/show.html.twig', [
            'categoria' => $categoria,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorias_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorias $categoria, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoriasType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorias_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorias/edit.html.twig', [
            'categoria' => $categoria,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorias_delete', methods: ['POST'])]
    public function delete(Request $request, Categorias $categoria, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoria->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categoria);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorias_index', [], Response::HTTP_SEE_OTHER);
    }
}
