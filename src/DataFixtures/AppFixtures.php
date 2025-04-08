<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Project;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    
    public function load(ObjectManager $manager): void
    {
        
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setName('testuser');

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($hashedPassword);

        $manager->persist($user);

        $projectsData = [
            [
                'title' => 'User Management API',
                'description' => 'A REST API for managing users, roles, and permissions.',
                'tasks' => [
                    'Design the database schema',
                    'Implement JWT authentication',
                    'Create CRUD endpoints',
                    'Add input validation',
                    'Test endpoints using Postman',
                ],
            ],
            [
                'title' => 'Startup Landing Page',
                'description' => 'A modern website to present a fictional startup.',
                'tasks' => [
                    'Design the page layout and UI',
                    'Integrate front-end with Twig templates',
                    'Configure the contact form',
                    'Add basic SEO tags',
                    'Deploy the site on a remote server',
                ],
            ],
            [
                'title' => 'Inventory Management System',
                'description' => 'An app to manage products and stock levels for a small store.',
                'tasks' => [
                    'Create entities: Product, Supplier, Stock',
                    'Implement stock in/out logic',
                    'Generate PDF reports',
                    'Add admin interface with EasyAdmin',
                    'Handle low stock alerts',
                ],
            ],
            [
                'title' => 'Note-Taking App',
                'description' => 'A lightweight app for organizing notes with categories.',
                'tasks' => [
                    'Create entities: Note, Category, User',
                    'Add Markdown editing support',
                    'Implement full-text search',
                    'Enable sorting and filtering of notes',
                    'Add a dark mode option',
                ],
            ],
        ];

        $colors = Project::$availableColors;
        $today = new \DateTime();

        foreach ($projectsData as $data) {
            $project = new Project();
            $project->setTitle($data['title']);
            $project->setDescription($data['description']);
            $project->setStartDate((clone $today)->modify('-' . rand(3, 10) . ' days'));
            $project->setEndDate((clone $today)->modify('+' . rand(10, 30) . ' days'));
            $project->setOwner($user);
            $project->setColor($colors[array_rand($colors)]);
            $project->addMember($user);

            foreach ($data['tasks'] as $taskTitle) {
                $task = new Task();
                $task->setTitle($taskTitle);
                $task->setDescription("Task for the project \"{$data['title']}\"");
                $task->setStatus('sprint');
                $task->setProgressState('pending');
                $task->setPriority(rand(0, 1) === 1);
                $task->setProject($project);
                $task->setResponsibleMember($user);
                $manager->persist($task);
            }

            $manager->persist($project);
        }

        $manager->flush();
    }
}