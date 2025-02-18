<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProjectController extends AbstractController
{

    private function getRandomColor(): string
    {
        $availableColors = Project::$availableColors;
        return $availableColors[array_rand($availableColors)];
    }
    
    #[Route('/create-project', name: 'create_project', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // set user
            $user = $this->getUser();
            $project->setOwner($user);

            // ser color
            $randomColor = $this->getRandomColor();
            $project->setColor($randomColor);

            // Handle form submission and persist the project
            $entityManager->persist($project);
            $entityManager->flush();

            // Redirect or show a success message
            return $this->redirectToRoute('app_home');
        }

        return $this->render('project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/project/{id}', name: 'project', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Project not found');
        }

        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);

    }

}