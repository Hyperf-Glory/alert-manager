<?php

declare(strict_types=1);
/**
 * This file is part of Task-Schedule.
 *
 * @license  https://github.com/Hyperf-Glory/Task-Schedule/main/LICENSE
 */
namespace HyperfGlory\AlertManager\Http;

use Hyperf\Guzzle\CoroutineHandler;
use HyperfGlory\AlertManager\ClientInterface;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $token = '';

    /**
     * @var array
     */
    protected $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
        $options = $config['options'] ?? [];
        if (empty($config['token'])) {
            throw new InvalidArgumentException('Token cannot be empty');
        }
        $this->token = $config['token'];
        if (! isset($options['base_uri'])) {
            $options['base_uri'] = sprintf('https://%s', 'oapi.dingtalk.com/robot/send');
        }
        if (! isset($options['handler']) && class_exists(CoroutineHandler::class)) {
            $options['handler'] = new CoroutineHandler();
        }
        $this->options = $options;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function send(array $params = []): ResponseInterface
    {
        $client = new \GuzzleHttp\Client($this->options);

        return $client->post($this->getRobotUrl(), array_merge($this->options, [
            'body' => json_encode($params, JSON_THROW_ON_ERROR),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'verify' => $this->config['ssl_verify'] ?? true,
        ]));
    }

    protected function getRobotUrl(): string
    {
        $query['access_token'] = $this->token;
        if (isset($this->config['secret']) && $secret = $this->config['secret']) {
            try {
                $timestamp = time() . sprintf('%03d', random_int(1, 999));
                $sign = hash_hmac('sha256', $timestamp . "\n" . $secret, $secret, true);
                $query['timestamp'] = $timestamp;
                $query['sign'] = base64_encode($sign);
            } catch (\Exception $e) {
            }
        }

        return $this->options['base_uri'] . '?' . http_build_query($query);
    }
}
