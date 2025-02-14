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

    #[Route('/api/calendar-events', name: 'api_calendar_events', methods: ['GET'])]
    public function calendarEvents(ProjectRepository $projectRepository): JsonResponse
    {
        $projects = $projectRepository->findAll();

        $colors = [
            '#aad3ff',   // Base Color: Light Blue
            '#ffaad3',   // Complementary 1: Light Pink (complementary color)
            '#ffb3c1',   // Complementary 2: Soft Pink (harmonizing)
            '#b3aad3',   // Harmonious 1: Lavender
            '#aad3c7',   // Harmonious 2: Soft Teal
            '#c7aad3',   // Harmonious 3: Soft Lavender
            '#ffb3ff',   // Complementary 3: Soft Purple
            '#aad3ff',   // Harmonious 4: Matching the original (light blue for continuity)
            '#aaffcc',   // Complementary 4: Mint Green
            '#ffccff',   // Complementary 5: Light Violet (soft and pastel)
        ];       
        
        $events = [];

        foreach ($projects as $project) {
            $events[] = [
                'title' => $project->getTitle(),
                'start' => $project->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $project->getEndDate()->format('Y-m-d H:i:s'),
                'display' => 'block',
                'backgroundColor' => $colors[array_rand($colors)],
                'borderColor' => '#ffffff',
                'textColor' => '#212529'
            ];
        }

        return new JsonResponse($events);
}

}