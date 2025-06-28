<?php

namespace ConduitUi\GitHubConnector\Contracts;

use Saloon\Http\Request;
use Saloon\Http\Response;

interface GithubConnectorInterface
{
    public function send(Request $request): Response;
}
