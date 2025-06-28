<?php

namespace ConduitUi\GitHubConnector;

use ConduitUi\GitHubConnector\Contracts\GithubConnectorInterface;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

/**
 * GitHub API connector for Saloon HTTP client.
 */
class GithubConnector extends Connector implements GithubConnectorInterface
{
    use AcceptsJson;

    protected ?string $token;

    /**
     * Create a new GitHub connector instance.
     *
     * @param  string|null  $token  GitHub personal access token
     */
    public function __construct(?string $token = null)
    {
        $this->token = $token;
    }

    /**
     * Get the base URL for the GitHub API.
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.github.com';
    }

    /**
     * Configure default authentication for requests.
     */
    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->token);
    }

    /**
     * Configure default headers for all requests.
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/vnd.github.v3+json',
            'X-GitHub-Api-Version' => '2022-11-28',
        ];
    }
}
