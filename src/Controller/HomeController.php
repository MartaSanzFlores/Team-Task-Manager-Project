<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Service\ProjectProgressService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_welcome')]
    public function welcome(): Response
    {
        return $this->render('home/welcome.html.twig');
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function dashboard(ProjectRepository $projectRepository, ProjectProgressService $progressService): Response
    {

        $user = $this->getUser();

        // get projects for the current user
        $projects = $projectRepository->findUserProjects($user);

        // calculate progress
        $progressData = [];
        foreach( $projects as $project) {
            $progress = $progressService->calculateProgress($project);

            $progressData[$project->getId()] = $progress;
        }


        return $this->render('home/home.html.twig', [
            'projects' => $projects,
            'progressData' => $progressData
        ]);
    }
}
