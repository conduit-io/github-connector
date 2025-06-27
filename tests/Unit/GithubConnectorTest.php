<?php

use ConduitUi\GitHubConnector\GithubConnector;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->connector = new GithubConnector('test-token');
});

it('can be instantiated with a token', function () {
    $connector = new GithubConnector('test-token');

    expect($connector)->toBeInstanceOf(GithubConnector::class);
});

it('can be instantiated without a token', function () {
    $connector = new GithubConnector;

    expect($connector)->toBeInstanceOf(GithubConnector::class);
});

it('resolves the correct base URL', function () {
    expect($this->connector->resolveBaseUrl())->toBe('https://api.github.com');
});

it('includes correct default headers', function () {
    $reflection = new ReflectionClass($this->connector);
    $method = $reflection->getMethod('defaultHeaders');
    $method->setAccessible(true);
    $headers = $method->invoke($this->connector);

    expect($headers)->toHaveKey('Accept', 'application/vnd.github.v3+json')
        ->and($headers)->toHaveKey('X-GitHub-Api-Version', '2022-11-28');
});

it('can make GET requests', function () {
    $mockClient = new MockClient([
        MockResponse::make(['message' => 'success'], 200),
    ]);

    $this->connector->withMockClient($mockClient);

    $response = $this->connector->get('/user');

    expect($response)->toBe(['message' => 'success']);
});

it('can make POST requests', function () {
    $mockClient = new MockClient([
        MockResponse::make(['created' => true], 201),
    ]);

    $this->connector->withMockClient($mockClient);

    $response = $this->connector->post('/user/repos', ['name' => 'test-repo']);

    expect($response)->toBe(['created' => true]);
});

it('can make PATCH requests', function () {
    $mockClient = new MockClient([
        MockResponse::make(['updated' => true], 200),
    ]);

    $this->connector->withMockClient($mockClient);

    $response = $this->connector->patch('/user', ['name' => 'Updated Name']);

    expect($response)->toBe(['updated' => true]);
});

it('can make PUT requests', function () {
    $mockClient = new MockClient([
        MockResponse::make(['replaced' => true], 200),
    ]);

    $this->connector->withMockClient($mockClient);

    $response = $this->connector->put('/user/starred/owner/repo');

    expect($response)->toBe(['replaced' => true]);
});

it('can make DELETE requests', function () {
    $mockClient = new MockClient([
        MockResponse::make([], 204),
    ]);

    $this->connector->withMockClient($mockClient);

    $response = $this->connector->delete('/user/starred/owner/repo');

    expect($response)->toBe([]);
});

it('passes query parameters correctly for GET requests', function () {
    $mockClient = new MockClient([
        MockResponse::make(['repos' => []], 200),
    ]);

    $this->connector->withMockClient($mockClient);

    $this->connector->get('/user/repos', ['type' => 'public', 'sort' => 'updated']);

    $mockClient->assertSent(function ($request) {
        return $request->query()->get('type') === 'public' &&
               $request->query()->get('sort') === 'updated';
    });
});

it('sends POST requests with correct endpoint', function () {
    $mockClient = new MockClient([
        MockResponse::make(['created' => true], 201),
    ]);

    $this->connector->withMockClient($mockClient);

    $this->connector->post('/user/repos', ['name' => 'test-repo', 'private' => true]);

    $mockClient->assertSent(function ($request) {
        return str_contains($request->resolveEndpoint(), '/user/repos');
    });
});
