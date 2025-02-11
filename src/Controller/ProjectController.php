<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProjectController extends AbstractController
{
    #[Route('/create-project', name: 'create_project', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            $project->setOwner($user);

            // Handle form submission and persist the project
            $entityManager->persist($project);
            $entityManager->flush();

            // Redirect or show a success message
            return $this->redirectToRoute('app_home'); // Redirige vers la page d'accueil
        }

        return $this->render('project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
