<?php

namespace App\Controller\Api;

use App\Repository\ProjectRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProjectController extends AbstractController
{

    #[Route('/api/calendar-events', name: 'api_calendar_events', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function calendarEvents(ProjectRepository $projectRepository): JsonResponse
    {
        $projects = $projectRepository->findAll();    
        
        $events = [];

        foreach ($projects as $project) {
            $events[] = [
                'title' => $project->getTitle(),
                'start' => $project->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $project->getEndDate()->format('Y-m-d H:i:s'),
                'display' => 'block',
                'backgroundColor' => $project->getColor(),
                'borderColor' => '#ffffff',
                'textColor' => '#212529'
            ];
        }

        return new JsonResponse($events);
}

}