<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Service\ProjectProgressService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProjectController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager
        ) {}

    private function getRandomColor(): string
    {
        $availableColors = Project::$availableColors;
        return $availableColors[array_rand($availableColors)];
    }
    
    #[Route('/create-project', name: 'create_project', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function create(Request $request): Response
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
            $this->entityManager->persist($project);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('project/form.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
            'is_edit' => false,
        ]);
    }

    #[Route('/edit-project/{id}', name: 'edit_project',methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(int $id, Request $request): Response
    {
        $project = $this->entityManager->getRepository(Project::class)->find($id);

        if ($project->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Access Denied');
        }

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('project/form.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
            'is_edit' => true,
        ]);
    }

    #[Route('/delete-project/{id}', name: 'delete_project', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(Request $request, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($project);
            $this->entityManager->flush();
        }

        // Redirect or show a success message
        return $this->redirectToRoute('app_dashboard');

    }

    #[Route('/project/{id}', name: 'project', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function show(int $id, ProjectProgressService $progressService): Response
    {
        $project = $this->entityManager->getRepository(Project::class)->find($id);
        $users = $this->entityManager->getRepository(User::class)->findAll();

        if (!$project) {
            throw $this->createNotFoundException('Project not found');
        }

        if ($project->getOwner() !== $this->getUser() && !$project->getMembers()->contains($this->getUser())) {
            throw $this->createAccessDeniedException('Access Denied');
        }

        $progress = $progressService->calculateProgress($project);

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'progress' => $progress,
            'users' => $users
        ]);

    }

}