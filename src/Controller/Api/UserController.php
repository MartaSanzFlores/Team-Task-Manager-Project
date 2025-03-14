<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController
{

    #[Route('api/profile/upload', name: 'profile_upload', methods: ['POST'])]
    public function uploadImage(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        $file = $request->files->get('profileImage');

        if ($file) {
            $newFilename = uniqid() . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('uploads_directory'), // Config dans services.yaml
                $newFilename
            );

            // Met à jour l'utilisateur avec le nouveau nom d'image
            $user->setProfileImage($newFilename);
            $entityManager->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }

}