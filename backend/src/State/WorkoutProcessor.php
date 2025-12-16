<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Workout;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class WorkoutProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $processor,
        private TokenStorageInterface $tokenStorage
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): mixed {
        if ($data instanceof Workout && !$data->getOwner()) {
            $token = $this->tokenStorage->getToken();
            $user = $token?->getUser();

            if (!$user || !is_object($user)) {
                throw new \RuntimeException('User not authenticated');
            }

            $data->setOwner($user);
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
