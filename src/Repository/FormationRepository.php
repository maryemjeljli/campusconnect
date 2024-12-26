<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @extends ServiceEntityRepository<Formation>
 */
class FormationRepository extends ServiceEntityRepository
{
    private string $uploadsDirectory;

    public function __construct(ManagerRegistry $registry, string $uploadsDirectory)
    {
        parent::__construct($registry, Formation::class);
        $this->uploadsDirectory = $uploadsDirectory;
    }

    /**
     * Gère l'upload et le changement d'image d'une formation.
     */
    public function handleImage(Formation $formation, $imageFile): void
    {
        if ($imageFile) {
            // Supprimer l'ancienne image si elle existe
            $this->deleteImage($formation);
    
            // Gérer le nom unique du fichier
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
    
            try {
                // Déplacer le fichier vers le répertoire d'uploads
                $imageFile->move($this->uploadsDirectory, $newFilename);
    
                // Associer le nom du fichier à l'entité Formation
                $formation->setImage($newFilename);
            } catch (FileException $e) {
                throw new FileException('Erreur lors du téléchargement de l\'image.');
            }
        }
    }
    

    /**
     * Supprime l'image associée à une formation.
     */
    public function deleteImage(Formation $formation): void
    {
        $imageFile = $formation->getImage();
        if ($imageFile) {
            $imagePath = $this->uploadsDirectory . '/' . $imageFile;
            if (file_exists($imagePath)) {
                unlink($imagePath);  // Supprimer le fichier
            }
        }
    }
}
