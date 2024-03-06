<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use DateTimeImmutable;
use Symfony\Bundle\SecurityBundle\Security;

class ProjectProcessor implements ProcessorInterface
{
    function __construct(
        private ProcessorInterface $persistProcessor,
        private Security $security,
    ) {
    }

    public function process(mixed $project, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // dd($project);
        if ($operation instanceof \ApiPlatform\Metadata\Post)
        {
            $project = $this->createProject($project);
        } else {
            $project = $this->updateProject($project);
        }

        $this->persistProcessor->process($project, $operation, $uriVariables, $context);

        return $project;
    }

    private function createProject($project)
    {
        $user = $this->security->getUser();
        $activities = $project->getActivities();
        $categories = $project->getCategories();

        $project->setCreatedAt(new DateTimeImmutable('now'));
        $project->setUpdatedAt(new DateTimeImmutable('now'));
        $project->setUser($user);

        $this->categoriesSetDateTime($categories);

        $activities->map(function ($activity) {
            $activity->setCreatedAt(new DateTimeImmutable('now'));
            $activity->setUpdatedAt(new DateTimeImmutable('now'));

            $this->categoriesSetDateTime($activity->getCategories());
        });

        return $project;
    }

    private function updateProject($project)
    {
        $user = $this->security->getUser();
        $activities = $project->getActivities();
        $categories = $project->getCategories();

        $project->setUpdatedAt(new DateTimeImmutable('now'));
        $project->setUser($user);

        $this->categoriesSetUpdatedAt($categories);

        $activities->map(function ($activity) {
            $activity->setUpdatedAt(new DateTimeImmutable('now'));

            $this->categoriesSetUpdatedAt($activity->getCategories());
        });

        return $project;
    }

    private function categoriesSetDateTime($categories)
    {
        $categories->map(function ($category) {
            $category->setCreatedAt(new DateTimeImmutable('now'));
            $category->setUpdatedAt(new DateTimeImmutable('now'));
        });
    }

    private function categoriesSetUpdatedAt($categories)
    {
        $categories->map(function ($category) {
            $category->setUpdatedAt(new DateTimeImmutable('now'));
        });
    }
}
