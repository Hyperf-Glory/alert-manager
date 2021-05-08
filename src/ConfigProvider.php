<?php

declare(strict_types=1);
/**
 * This file is part of Task-Schedule.
 *
 * @license  https://github.com/Hyperf-Glory/Task-Schedule/main/LICENSE
 */
namespace HyperfGlory\AlertManager;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'listeners' => [],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'description of this config file.',
                    'source' => __DIR__ . '/../publish/alert.php',
                    'destination' => BASE_PATH . '/config/autoload/alert.php',
                ],
            ],
        ];
    }
}
