<?php

namespace App\Controller;

use App\Entity\Typestage;
use App\Form\TypestageType;
use App\Repository\TypestageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/typestage')]
final class TypestageController extends AbstractController{
    #[Route(name: 'app_typestage_index', methods: ['GET'])]
    public function index(TypestageRepository $typestageRepository): Response
    {
        return $this->render('typestage/index.html.twig', [
            'typestages' => $typestageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_typestage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typestage = new Typestage();
        $form = $this->createForm(TypestageType::class, $typestage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typestage);
            $entityManager->flush();

            return $this->redirectToRoute('app_typestage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('typestage/new.html.twig', [
            'typestage' => $typestage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typestage_show', methods: ['GET'])]
    public function show(Typestage $typestage): Response
    {
        return $this->render('typestage/show.html.twig', [
            'typestage' => $typestage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_typestage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Typestage $typestage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypestageType::class, $typestage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_typestage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('typestage/edit.html.twig', [
            'typestage' => $typestage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typestage_delete', methods: ['POST'])]
    public function delete(Request $request, Typestage $typestage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typestage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($typestage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_typestage_index', [], Response::HTTP_SEE_OTHER);
    }
}
