<?php

use ConduitUi\GitHubConnector\Exceptions\GithubAuthException;
use ConduitUi\GitHubConnector\Exceptions\GitHubException;
use ConduitUi\GitHubConnector\Exceptions\GitHubRateLimitException;
use ConduitUi\GitHubConnector\Exceptions\GitHubResourceNotFoundException;
use ConduitUi\GitHubConnector\Exceptions\GitHubValidationException;

it('can create a basic GitHub exception', function () {
    $exception = new GitHubException('Test error');

    expect($exception->getMessage())->toBe('Test error')
        ->and($exception->getResponse())->toBeNull()
        ->and($exception->getGitHubError())->toBeNull()
        ->and($exception->getRecoverySuggestion())->toBeNull();
});

it('creates auth exception with recovery suggestion', function () {
    $exception = new GithubAuthException('Auth failed');

    expect($exception->getCode())->toBe(401)
        ->and($exception->getRecoverySuggestion())
        ->toBe('Check your GitHub token is valid and has the required permissions.');
});

it('creates rate limit exception with default values', function () {
    $exception = new GitHubRateLimitException('Rate limited');

    expect($exception->getCode())->toBe(403)
        ->and($exception->getLimit())->toBeNull()
        ->and($exception->getRemaining())->toBeNull()
        ->and($exception->getResetTime())->toBeNull()
        ->and($exception->getRecoverySuggestion())->toContain('Wait for rate limit');
});

it('creates resource not found exception', function () {
    $exception = new GitHubResourceNotFoundException('Resource missing');

    expect($exception->getCode())->toBe(404)
        ->and($exception->getRecoverySuggestion())
        ->toBe('Check that the repository, user, or resource exists and you have access to it.');
});

it('creates validation exception with default values', function () {
    $exception = new GitHubValidationException('Validation failed');

    expect($exception->getCode())->toBe(422)
        ->and($exception->getValidationErrors())->toBe([])
        ->and($exception->getRecoverySuggestion())
        ->toBe('Check the request data format and ensure all required fields are provided correctly.');
});
