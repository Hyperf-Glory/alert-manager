<?php

declare(strict_types=1);
/**
 * This file is part of Task-Schedule.
 *
 * @license  https://github.com/Hyperf-Glory/Task-Schedule/main/LICENSE
 */
namespace HyperfGlory\AlertManager;

use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    /**
     * Create and send an HTTP request.
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param array $params Request parameters
     *
     * @throws \Throwable
     */
    public function send(array $params = []): ResponseInterface;
}
