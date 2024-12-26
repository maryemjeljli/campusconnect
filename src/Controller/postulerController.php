<?php

namespace App\Controller;

use App\Entity\Postuler;
use App\Form\PostulerType;
use App\Entity\Stage; // Importez l'entité Stage
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class postulerController extends AbstractController
{
    #[Route('/postuler/{id}', name: 'app_stage_postuler')]
    public function postuler(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le stage depuis la base de données
        $stage = $entityManager->getRepository(Stage::class)->find($id);

        if (!$stage) {
            throw $this->createNotFoundException('Stage introuvable.');
        }

        // Créer une nouvelle postulation
        $postuler = new Postuler();

        // Pré-remplir les champs avec les informations du User connecté
        $user = $this->getUser();
        if ($user) {
            $postuler->setNom($user->getNom());
            $postuler->setEmail($user->getEmail());
            
            // Vérifier si le User possède la méthode getPhone
            if (method_exists($user, 'getPhone') && $user->getPhone()) {
                $postuler->setPhone($user->getPhone());
            }
        }

        // Créer le formulaire de postulation
        $form = $this->createForm(PostulerType::class, $postuler);
        $form->handleRequest($request);

        // Gestion de la soumission du formulaire
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->persist($postuler);
                $entityManager->flush();

                $this->addFlash('success', 'Votre candidature a été envoyée avec succès !');

                return $this->redirectToRoute('app_home');
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs.');
            }
        }

        // Rendu de la vue Twig
        return $this->render('stage/postuler.html.twig', [
            'stage' => $stage,
            'form' => $form->createView(),
        ]);
    }
}
