<?php

declare(strict_types=1);
/**
 * This file is part of Task-Schedule.
 *
 * @license  https://github.com/Hyperf-Glory/Task-Schedule/main/LICENSE
 */
namespace HyperfGlory\AlertManager\Messages;

use HyperfGlory\AlertManager\DingTalkService;

class FeedCard extends Message
{
    protected $service;

    public function __construct(DingTalkService $service)
    {
        $this->service = $service;
        $this->setMessage();
    }

    public function setMessage(): void
    {
        $this->message = [
            'feedCard' => [
                'links' => [],
            ],
            'msgtype' => 'feedCard',
        ];
    }

    public function addLinks($title, $messageUrl, $picUrl): FeedCard
    {
        $this->message['feedCard']['links'][] = [
            'title' => $title,
            'messageURL' => $messageUrl,
            'picURL' => $picUrl,
        ];

        return $this;
    }

    public function send()
    {
        $this->service->setMessage($this);

        return $this->service->send();
    }
}
