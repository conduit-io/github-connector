<?php

namespace ConduitUi\GitHubConnector\Exceptions;

use Exception;

/**
 * Exception thrown when GitHub authentication fails.
 *
 * This exception is thrown when there are issues with GitHub API authentication,
 * such as invalid tokens, expired credentials, or insufficient permissions.
 */
class GithubAuthException extends Exception {}
