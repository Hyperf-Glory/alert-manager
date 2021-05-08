<?php

declare(strict_types=1);
/**
 * This file is part of Task-Schedule.
 *
 * @license  https://github.com/Hyperf-Glory/Task-Schedule/main/LICENSE
 */
namespace HyperfGlory\AlertManager\Messages;

class Link extends Message
{
    public function __construct($title, $text, $messageUrl, $picUrl = '')
    {
        $this->setMessage($title, $text, $messageUrl, $picUrl);
    }

    public function setMessage($title, $text, $messageUrl, $picUrl = ''): void
    {
        $this->message = [
            'msgtype' => 'link',
            'link' => [
                'text' => $text,
                'title' => $title,
                'picUrl' => $picUrl,
                'messageUrl' => $messageUrl,
            ],
        ];
    }
}
