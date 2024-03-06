<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Project;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ActivityProcessor implements ProcessorInterface
{
    function __construct(
        // private ProcessorInterface $persistProcessor,
        // private Security $security,
        // private EntityManagerInterface $entityManager
    ) {
    }

    public function process(mixed $activity, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $repository = $this->entityManager->getRepository(Project::class);
        $user = $this->security->getUser();

        if ($operation instanceof \ApiPlatform\Metadata\Post)
        {
            $activity->setCreatedAt(new DateTimeImmutable('now'));
        }
        // $project = $repository->getLastInsertByUser($user->getId());

        $activity->setUpdatedAt(new DateTimeImmutable('now'));
        // $activity->setProject($project);

        $this->persistProcessor->process($activity, $operation, $uriVariables, $context);

        return $activity;
    }
}
