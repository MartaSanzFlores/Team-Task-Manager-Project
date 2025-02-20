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

    #[Route('/home', name: 'app_home')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function home(ProjectRepository $projectRepository, ProjectProgressService $progressService): Response
    {

        // get all projects
        $projects = $projectRepository->findAll();

        // get progress
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
