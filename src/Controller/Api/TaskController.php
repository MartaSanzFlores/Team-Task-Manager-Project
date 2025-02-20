<?php

namespace App\Controller\Api;

use App\Repository\TaskRepository;
use App\Service\ProjectProgressService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TaskController extends AbstractController
{

    #[Route('/project/api/update-task-status/task-{id}', name: 'update_task_status', methods: ['POST'])]
    public function updateTaskStatus(int $id, Request $request, EntityManagerInterface $entityManager, TaskRepository $taskRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = $taskRepository->find($id);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], 404);
        }

        if (!isset($data['status'])) {
            return new JsonResponse(['error' => 'Missing status'], 400);
        }

        $task->setStatus($data['status']);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/project/api/update-task-progressState/task-{id}', name: 'update_task_progressState', methods: ['POST'])]
    public function updateTaskProgressState(int $id, Request $request, EntityManagerInterface $entityManager, TaskRepository $taskRepository, ProjectProgressService $progressService ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = $taskRepository->find($id);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], 404);
        }

        if (!isset($data['progressState'])) {
            return new JsonResponse(['error' => 'Missing progress state'], 400);
        }

        $task->setProgressState($data['progressState']);
        $entityManager->flush();

        //progress project calcule
        $project = $task->getProject();
        $progress = $progressService->calculateProgress($project);
        $projectColor = $project->getColor();

        return new JsonResponse([
            'success' => true,
            'progress' => $progress,
            'color' => $projectColor
        ]);
    }


}
