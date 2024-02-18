<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsDecorator('api_platform.doctrine.orm.state.persist_processor')]
class UserHashPasswordStateProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $innerProcessor,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof User && $data->getPlainPassword()) {
            $data->setPassword($this->passwordHasher->hashPassword(
                $data,
                $data->getPlainPassword()
            ));
            // $data->eraseCredentials();
        }

        return $this->innerProcessor->process($data, $operation, $uriVariables, $context);
    }
}