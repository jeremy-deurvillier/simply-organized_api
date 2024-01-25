<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use DateTimeImmutable;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private ProcessorInterface $removeProcessor,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function process(mixed $user, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $uuid = Uuid::v7()->toRfc4122();
        $password = $this->passwordHasher->hashPassword($user, $user->getPassword());

        $user->setUuid($uuid);
        $user->setPassword($password);
        $user->setRoles(["ROLE_USER"]);
        $user->setCreatedAt(new DateTimeImmutable('now'));
        $user->setUpdatedAt(new DateTimeImmutable('now'));

        $this->persistProcessor->process($user, $operation, $uriVariables, $context);

        return $user;
    }
}
