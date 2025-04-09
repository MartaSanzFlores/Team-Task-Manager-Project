<?php

namespace App\Tests\Service;

use App\Entity\Project;
use App\Repository\TaskRepository;
use App\Service\ProjectProgressService;
use PHPUnit\Framework\TestCase;

class ProjectProgressServiceTest extends TestCase
{
    public function testCalculateProgressReturnsCorrectValue(): void
    {
        // Mock TaskRepository
        $taskRepositoryMock = $this->createMock(TaskRepository::class);
        
        // Create a project
        $project = new Project();

        // Mock configuration
        $taskRepositoryMock
            ->expects($this->once())
            ->method('getTaskCompletionPercentage')
            ->with($project)
            ->willReturn(75);

        // Inject mock in the service
        $progressService = new ProjectProgressService($taskRepositoryMock);

        $result = $progressService->calculateProgress($project);

        $this->assertEquals(75, $result);
    }
}