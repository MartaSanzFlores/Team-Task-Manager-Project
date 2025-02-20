<?php

namespace App\Service;

use App\Entity\Project;
use App\Repository\TaskRepository;

class ProjectProgressService
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function calculateProgress(Project $project): int
    {
        return $this->taskRepository->getTaskCompletionPercentage($project);
    }
}



