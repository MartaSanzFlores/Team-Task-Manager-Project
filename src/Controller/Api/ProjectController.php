<?php

namespace App\Controller\Api;

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

    #[Route('/api/calendar-events', name: 'api_calendar_events', methods: ['GET'])]
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