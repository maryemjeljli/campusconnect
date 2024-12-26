<?php

namespace App\Controller;

use App\Entity\Typeclub;
use App\Form\TypeClubType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeclubRepository;
use Symfony\Component\HttpFoundation\Request;

class TypeClubController extends AbstractController
{
    
    #[Route('/type/club', name: 'app_type_club')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les types de clubs
        $types = $entityManager->getRepository(Typeclub::class)->findAll();

        return $this->render('typeclub/add.html.twig', [
            'types' => $types,
        ]);
       
    }

    #[Route('/typeclubs/add', name: 'typeclub_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer un nouveau Typeclub
        $typeclub = new Typeclub();

        // Créer le formulaire
        $form = $this->createForm(TypeclubType::class, $typeclub);

        // Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            // Persister le type de club
            $entityManager->persist($typeclub);
            $entityManager->flush();

            // Rediriger après la soumission
            return $this->redirectToRoute('typeclub_add');
        }
        $types = $entityManager->getRepository(Typeclub::class)->findAll();


        return $this->render('typeclub/add.html.twig', [
            'form' => $form->createView(),
            'types' => $types,
        ]);
    }

    #[Route('/typeclubs/edit/{id}', name: 'typeclub_edit')]
    public function editTypeclub($id, Request $request, EntityManagerInterface $entityManager,TypeclubRepository $repo): Response
    {
        
        $typeclub = $repo->find($id);

       

        // Créer le formulaire pour modifier le type de club
        $form = $this->createForm(TypeClubType::class, $typeclub);

        // Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Sauvegarder les modifications
            $entityManager->flush();

            // Rediriger après la modification
            return $this->redirectToRoute('typeclub_add');
        }
        $types = $entityManager->getRepository(Typeclub::class)->findAll();


        return $this->render('typeclub/edit.html.twig', [
            'form' => $form->createView(),
            'typeclub' => $typeclub,
            'types' => $types,
        ]);
    }

    #[Route('/typeclubs/delete/{id}', name: 'typeclub_delete')]
    public function delete($id, EntityManagerInterface $entityManager,TypeclubRepository $repo): Response
    {
        // Récupérer le type de club par ID
        $typeclub = $repo->find($id);

    

        // Supprimer le type de club
        $entityManager->remove($typeclub);
        $entityManager->flush();

        // Rediriger après la suppression
        return $this->redirectToRoute('typeclub_add');
    }  
}
