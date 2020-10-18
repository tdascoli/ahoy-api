<?php
declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;

class ProfileTokenAction extends AuthAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        // Validate login
        $isValidLogin = $this->auth->verify($data->deviceId, $data->password);

        if (!$isValidLogin) {
            // Invalid authentication credentials
            return $this->response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401, 'Unauthorized');
        }

        // Create a fresh token
        $token = $this->jwtAuth->createJwt($data->deviceId);
        $lifetime = $this->jwtAuth->getLifetime();

        // Transform the result into a OAuh 2.0 Access Token Response
        // https://www.oauth.com/oauth2-servers/access-tokens/access-token-response/
        $result = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $lifetime,
        ];

        // Build the HTTP response
        return $this->respondWithData($result)
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
