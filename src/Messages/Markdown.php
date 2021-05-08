<?php

declare(strict_types=1);
/**
 * This file is part of Task-Schedule.
 *
 * @license  https://github.com/Hyperf-Glory/Task-Schedule/main/LICENSE
 */
namespace HyperfGlory\AlertManager\Messages;

class Markdown extends Message
{
    public function __construct($title, $markdown)
    {
        $this->setMessage($title, $markdown);
    }

    public function setMessage($title, $markdown): void
    {
        $this->message = [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $title,
                'text' => $markdown,
            ],
        ];
    }
}
