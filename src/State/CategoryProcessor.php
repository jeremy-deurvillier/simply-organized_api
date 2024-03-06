<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Project;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CategoryProcessor implements ProcessorInterface
{
    function __construct(
        // private ProcessorInterface $persistProcessor,
        // private Security $security,
        // private EntityManagerInterface $entityManager
    ) {
    }

    public function process(mixed $category, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $repository = $this->entityManager->getRepository(Project::class);
        $user = $this->security->getUser();

        // dd(get_class($operation));
        if ($operation instanceof \ApiPlatform\Metadata\Post)
        {
            $category->setCreatedAt(new DateTimeImmutable('now'));
        }

        if ($operation instanceof \ApiPlatform\Metadata\Patch)
        {
            $category->setUpdatedAt(new DateTimeImmutable('now'));
        }


        // $project = $repository->getLastInsertByUser($user->getId());

        // $category->addProject($project);

        $this->persistProcessor->process($category, $operation, $uriVariables, $context);

        return $category;
    }
}
