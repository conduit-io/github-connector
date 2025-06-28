<?php

namespace ConduitUi\GitHubConnector;

use ConduitUi\GitHubConnector\Contracts\GithubConnectorInterface;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

/**
 * GitHub API connector providing HTTP client functionality.
 *
 * This class extends Saloon's Connector to provide a specialized HTTP client
 * for interacting with the GitHub API. It handles authentication, request
 * formatting, and basic transport configuration.
 *
 * This is a PURE TRANSPORT LAYER - no business logic or HTTP method delegation.
 */
class GithubConnector extends Connector implements GithubConnectorInterface
{
    use AcceptsJson;

    /**
     * GitHub personal access token for authentication.
     */
    protected ?string $token;

    /**
     * Create a new GitHub connector instance.
     *
     * @param  string|null  $token  GitHub personal access token (optional)
     */
    public function __construct(?string $token = null)
    {
        $this->token = $token;
    }

    /**
     * Get the base URL for the GitHub API.
     *
     * @return string The GitHub API base URL
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.github.com';
    }

    /**
     * Configure default authentication for requests.
     *
     * @return TokenAuthenticator Token-based authentication handler
     */
    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->token);
    }

    /**
     * Configure default headers for all requests.
     *
     * @return array Default headers including GitHub API version and content type
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/vnd.github.v3+json',
            'X-GitHub-Api-Version' => '2022-11-28',
        ];
    }

    // REMOVED: All HTTP method delegation (get, post, put, patch, delete)
    // REMOVED: sendRequest() method with inline request creation
    // REMOVED: Business logic - this is now a PURE TRANSPORT LAYER

    // The github-client package will handle request creation and business logic
    // This connector only provides authentication, headers, and base URL
}
