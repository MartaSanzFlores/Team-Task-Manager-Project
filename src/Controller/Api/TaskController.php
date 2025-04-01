<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Service\ProjectProgressService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TaskController extends AbstractController
{

    private function getTaskOrFail(int $id, TaskRepository $taskRepository): ?Task
    {
        $task = $taskRepository->find($id);
        if (!$task) {
            throw $this->createNotFoundException('Task not found');
        }
        return $task;
    }

    #[Route('/project/api/update-task-status/task-{id}', name: 'update_task_status', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function updateTaskStatus(int $id, Request $request, EntityManagerInterface $entityManager, TaskRepository $taskRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = $this->getTaskOrFail($id, $taskRepository);

        $task->setStatus($data['status']);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/project/api/update-task-progressState/task-{id}', name: 'update_task_progressState', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function updateTaskProgressState(int $id, Request $request, EntityManagerInterface $entityManager, TaskRepository $taskRepository, ProjectProgressService $progressService ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = $this->getTaskOrFail($id, $taskRepository);

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

    #[Route('/project/api/update-task/{taskId}', name: 'update_task', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function updateTask(Request $request, $taskId, EntityManagerInterface $entityManager): JsonResponse
    {
        $task = $entityManager->getRepository(Task::class)->find($taskId);
    
        if (!$task) {
            return new JsonResponse(['message' => 'Task not found'], 404);
        }
    
        $data = json_decode($request->getContent(), true);

    
        // Update responsible
        if (isset($data['responsible'])) {
            $responsible = $entityManager->getRepository(User::class)->find($data['responsible']);
            dump($responsible);
            if ($responsible) {
                $task->setResponsibleMember($responsible);
            } else {
                return new JsonResponse(['message' => 'Responsible user not found'], 404);
            }
        }
    
        // Update priority
        if (isset($data['priority'])) {
            $validPriorities = ['normal', 'high'];
            if (in_array($data['priority'], $validPriorities)) {
                if($data['priority'] == 'high') {
                    $task->setPriority(true);
                } elseif($data['priority'] == 'normal') {
                    $task->setPriority(false);
                } 
                
            } else {
                return new JsonResponse(['message' => 'Invalid priority value'], 400);
            }
        }
    
        // Update description
        if (isset($data['description'])) {
            $task->setDescription($data['description']);
        }
    
        $entityManager->flush();
    
        return new JsonResponse([
            'message' => 'Task updated successfully!',
            'task' => [
                'id' => $task->getId(),
                'responsible' => $task->getResponsibleMember()?->getName(),
                'priority' => $task->getPriority(),
                'description' => $task->getDescription(),
            ]
        ]);
    }

}
