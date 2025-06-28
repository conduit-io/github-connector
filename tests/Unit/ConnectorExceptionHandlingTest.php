<?php

use ConduitUi\GitHubConnector\Exceptions\GithubAuthException;
use ConduitUi\GitHubConnector\Exceptions\GitHubForbiddenException;
use ConduitUi\GitHubConnector\Exceptions\GitHubRateLimitException;
use ConduitUi\GitHubConnector\Exceptions\GitHubResourceNotFoundException;
use ConduitUi\GitHubConnector\Exceptions\GitHubServerException;
use ConduitUi\GitHubConnector\Exceptions\GitHubValidationException;
use ConduitUi\GitHubConnector\GithubConnector;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->connector = new GithubConnector('test-token');
});

it('creates correct exception for 401 status', function () {
    $response = MockResponse::make(['message' => 'Bad credentials'], 401);

    $exception = $this->connector->getRequestException($response);

    expect($exception)->toBeInstanceOf(GithubAuthException::class)
        ->and($exception->getMessage())->toBe('GitHub authentication failed');
});

it('creates rate limit exception for 403 with rate limit headers', function () {
    $response = MockResponse::make(['message' => 'API rate limit exceeded'], 403, [
        'X-RateLimit-Remaining' => '0',
    ]);

    $exception = $this->connector->getRequestException($response);

    expect($exception)->toBeInstanceOf(GitHubRateLimitException::class);
});

it('creates forbidden exception for 403 without rate limit', function () {
    $response = MockResponse::make(['message' => 'Forbidden'], 403);

    $exception = $this->connector->getRequestException($response);

    expect($exception)->toBeInstanceOf(GitHubForbiddenException::class);
});

it('creates correct exception for 404 status', function () {
    $response = MockResponse::make(['message' => 'Not Found'], 404);

    $exception = $this->connector->getRequestException($response);

    expect($exception)->toBeInstanceOf(GitHubResourceNotFoundException::class)
        ->and($exception->getMessage())->toBe('GitHub resource not found');
});

it('creates correct exception for 422 status', function () {
    $response = MockResponse::make(['message' => 'Validation Failed'], 422);

    $exception = $this->connector->getRequestException($response);

    expect($exception)->toBeInstanceOf(GitHubValidationException::class)
        ->and($exception->getMessage())->toBe('GitHub API validation failed');
});

it('creates correct exception for 500 status', function () {
    $response = MockResponse::make(['message' => 'Internal Server Error'], 500);

    $exception = $this->connector->getRequestException($response);

    expect($exception)->toBeInstanceOf(GitHubServerException::class)
        ->and($exception->getMessage())->toBe('GitHub API server error');
});
