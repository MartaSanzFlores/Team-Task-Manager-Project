<?php

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class TaskTest extends KernelTestCase
{
    public function testGettersAndSetters()
    {
        $task = new Task();

        $task->setTitle('Test Title');
        $task->setDescription('Test Description');

        $this->assertSame('Test Title', $task->getTitle());
        $this->assertSame('Test Description', $task->getDescription());
    }

    public function testTaskProjectRelation()
    {
        // project creation
        $project = new Project();
        $project->setTitle('Test Project');
        $project->setDescription('Description of the project');
        $project->setStartDate(new \DateTimeImmutable());
        $project->setEndDate(new \DateTimeImmutable());

        // task creation
        $task = new Task();
        $task->setTitle('Test Task');
        $task->setDescription('Description of the task');
        $task->setProject($project); // Set the Project for this Task

        // Verify the relationship
        $this->assertSame($project, $task->getProject());
    }

    public function testUserRelation()
    {
        // user creation
        $user = new User();

        // task creation
        $task = new Task();
        $task->setTitle('Test Task');
        $task->setDescription('Description of the task');
        $task->setResponsibleMember($user);

        // Verify the relationship
        $this->assertSame($user, $task->getResponsibleMember());
    }

    public function testInvalidEntity()
    {
        // empty task
        $task = new Task();

        self::bootKernel();
        $validator = static::getContainer()->get('validator');
        $errors = $validator->validate($task);

        $this->assertGreaterThan(0, count($errors));
    }

}