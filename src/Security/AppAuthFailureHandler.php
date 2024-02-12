<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AppAuthFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $json = json_encode(['something' => 'went wrong']);

        $response = new Response($json, Response::HTTP_UNAUTHORIZED);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}