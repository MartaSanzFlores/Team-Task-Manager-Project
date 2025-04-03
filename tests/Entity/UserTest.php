<?php

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class UserTest extends KernelTestCase
{
    public function testUserProjectRelation()
    {
        // Create a User
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $user->setName('John Doe');
        $user->setPassword('hashed_password'); // A hashed password

        // Create a Project
        $project = new Project();
        $project->setTitle('Test Project');
        $project->setDescription('Description of the project');
        $project->setStartDate(new \DateTimeImmutable());
        $project->setEndDate(new \DateTimeImmutable());
        $project->setOwner($user); // Set the User as the owner of the Project

        // Add project to the user
        $user->addProject($project);

        // Assert that the project is correctly added to the user's projects collection
        $this->assertCount(1, $user->getProjects());
        $this->assertSame($project, $user->getProjects()->first());
    }

    public function testUserTaskRelation()
    {
        // Create a User
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $user->setName('John Doe');
        $user->setPassword('hashed_password');

        // Create a Task
        $task = new Task();
        $task->setTitle('Test Task');
        $task->setDescription('Description of the task');
        $task->setResponsibleMember($user); // Set the User as the responsible member

        // Add task to the user
        $user->addTask($task);

        // Assert that the task is correctly added to the user's tasks collection
        $this->assertCount(1, $user->getTasks());
        $this->assertSame($task, $user->getTasks()->first());
    }

    public function testAddRemoveProject()
    {
        // Create a User
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $user->setName('John Doe');
        $user->setPassword('hashed_password');

        // Create a Project
        $project = new Project();
        $project->setTitle('Test Project');
        $project->setDescription('Description of the project');
        $project->setStartDate(new \DateTimeImmutable());
        $project->setEndDate(new \DateTimeImmutable());

        // Add the project to the user
        $user->addProject($project);
        $this->assertCount(1, $user->getProjects());

        // Remove the project from the user
        $user->removeProject($project);
        $this->assertCount(0, $user->getProjects());
    }

    public function testUserPassword()
    {
        // Create a User
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $user->setName('John Doe');
        $user->setPassword('hashed_password');

        // Check if the password is correctly set
        $this->assertEquals('hashed_password', $user->getPassword());
    }

    public function testUserUniqueEmailValidation()
    {
        $user1 = new User();
        $user1->setEmail('john.doe@example.com');
        $user1->setName('John Doe');
        $user1->setPassword('hashed_password');

        self::bootKernel();
        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
    
        // Persist and flush the first user to save it to the database
        $entityManager->persist($user1);
        $entityManager->flush(); 

        $validator = static::getContainer()->get('validator');

        // Validate the first user
        $errors1 = $validator->validate($user1);
        $this->assertCount(0, $errors1); // No validation errors for the first user

        $user2 = new User();
        $user2->setEmail('john.doe@example.com');
        $user2->setName('Jane Doe');
        $user2->setPassword('hashed_password');

        // Validate the second user
        $errors2 = $validator->validate($user2);
        $this->assertGreaterThan(0, count($errors2)); // Error validation
    }

}