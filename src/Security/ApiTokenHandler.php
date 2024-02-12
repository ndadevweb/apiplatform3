<?php

namespace App\Security;

use App\Repository\ApiTokensRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class ApiTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(private ApiTokensRepository $apiTokensRepository)
    {

    }

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        $token = $this->apiTokensRepository->findOneBy(['token' => $accessToken]);

        if($token === null) {
            throw new BadCredentialsException();
        }

        if($token->isValid() === false) {
            throw new CustomUserMessageAuthenticationException('Token expired');
        }

        $token->getOwnedBy()->markAsTokenAuthenticated($token->getScopes());

        return new UserBadge($token->getOwnedBy()->getUserIdentifier());
    }
}