<?php

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ProjectTest extends KernelTestCase
{
    public function testGettersAndSetters()
    {
        $project = new Project();

        $project->setTitle('Test Title');
        $project->setDescription('Test Description');
        $date = new \DateTimeImmutable();
        $project->setStartDate($date);
        $project->setEndDate($date);

        $this->assertSame('Test Title', $project->getTitle());
        $this->assertSame('Test Description', $project->getDescription());
        $this->assertSame($date, $project->getStartDate());
        $this->assertSame($date, $project->getEndDate());
    }

    public function testAddRemoveMembers()
    {
        $project = new Project();
        $user = new User(); // Assure-toi que User existe
        $project->addMember($user);

        $this->assertCount(1, $project->getMembers());
        $this->assertTrue($project->getMembers()->contains($user));

        $project->removeMember($user);
        $this->assertCount(0, $project->getMembers());
    }

    public function testAddRemoveTasks()
    {
        $project = new Project();
        $task = new Task();
        $project->addTask($task);

        $this->assertCount(1, $project->getTasks());
        $this->assertTrue($project->getTasks()->contains($task));

        $project->removeTask($task);
        $this->assertCount(0, $project->getTasks());
    }

    public function testValidColor()
    {
        $project = new Project();
        $project->setColor('#d4e9ff');

        $this->assertSame('#d4e9ff', $project->getColor());
    }


    public function testInvalidEntity()
    {
        // empty project
        $project = new Project();

        self::bootKernel();
        $validator = static::getContainer()->get('validator');
        $errors = $validator->validate($project);

        $this->assertGreaterThan(0, count($errors));
    }

}

