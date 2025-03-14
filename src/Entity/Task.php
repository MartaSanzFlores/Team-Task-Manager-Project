<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(choices: ['backlog', 'sprint', 'finished'], message: 'Invalid status')]
    private ?string $status = "backlog";

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: ['pending', 'ongoing', 'ko', 'done'], message: 'Invalid progress state')]
    private string $progressState = 'pending';

    #[ORM\Column(type: 'boolean')]
    private bool $priority = false;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?User $responsibleMember = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $Title): static
    {
        $this->title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of priority
     */ 
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set the value of priority
     *
     * @return  self
     */ 
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get the value of progressState
     */ 
    public function getProgressState()
    {
        return $this->progressState;
    }

    /**
     * Set the value of progressState
     *
     * @return  self
     */ 
    public function setProgressState($progressState)
    {
        $this->progressState = $progressState;

        return $this;
    }

    public function getResponsibleMember(): ?User
    {
        return $this->responsibleMember;
    }

    public function setResponsibleMember(?User $responsibleMember): static
    {
        $this->responsibleMember = $responsibleMember;

        return $this;
    }
}