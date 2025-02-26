<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TaskController extends AbstractController
{

    #[Route('/project/{id}/create-task', name: 'create_task')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createTask(Project $project, Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $task->setProject($project);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('project', ['id' => $project->getId()]);
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

}
