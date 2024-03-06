<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ProjectProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager
    )
    {
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        $repository = $this->entityManager->getRepository(Project::class);
        $projectId = basename($context['request']->getPathInfo());

        if ($operation instanceof CollectionOperationInterface) {
            return $repository->getProjectsByUser($user->getId());
        }

        return $repository->getProjectByIdAndUser(intval($projectId), $user->getId());
    }
}
