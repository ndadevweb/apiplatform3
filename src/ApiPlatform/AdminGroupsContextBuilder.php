<?php

namespace App\ApiPlatform;

use ApiPlatform\Serializer\SerializerContextBuilder;
use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\HttpFoundation\Request;

#[AsDecorator('api_platform.serializer.context_builder')]
class AdminGroupsContextBuilder implements SerializerContextBuilderInterface
{
    public function __construct(
        private SerializerContextBuilder $decorated,
        private Security $security
    ) {}

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

        if(isset($context['groups']) === true && $this->security->isGranted('ROLE_ADMIN') === true) {
            $context['groups'][] = $normalization ? 'admin:read' : 'admin:write';
        }

        return $context;
    }    
}