<?php

declare(strict_types=1);
/**
 * This file is part of Task-Schedule.
 *
 * @license  https://github.com/Hyperf-Glory/Task-Schedule/main/LICENSE
 */
namespace HyperfGlory\AlertManager;

class DingTalk
{
    /**
     * @var
     */
    protected $config;

    /**
     * @var string
     */
    protected $robot = 'default';

    /**
     * @var DingTalkService
     */
    protected $dingTalkService;

    protected $client;

    /**
     * DingTalk constructor.
     *
     * @param $config
     * @param null|\HyperfGlory\AlertManager\Http\Client $client
     */
    public function __construct($config = null, Http\Client $client = null)
    {
        $this->config = $config ?? config('alert', []);
        $this->client = $client;
        $this->with();
    }

    /**
     * @return $this
     */
    public function with(string $robot = 'default'): self
    {
        $this->robot = $robot;
        $this->dingTalkService = new DingTalkService($this->config[$robot], $this->client);

        return $this;
    }

    /**
     * @return false|\Psr\Http\Message\ResponseInterface
     */
    public function text(string $content = '')
    {
        return $this->dingTalkService
            ->setTextMessage($content)
            ->send();
    }

    /**
     * @param $title
     * @param $text
     *
     * @return \HyperfGlory\AlertManager\Messages\ActionCard|\HyperfGlory\AlertManager\Messages\Message
     */
    public function action($title, $text)
    {
        return $this->dingTalkService
            ->setActionCardMessage($title, $text);
    }

    /**
     * @return $this
     */
    public function at(array $mobiles = [], bool $atAll = false): self
    {
        $this->dingTalkService
            ->setAt($mobiles, $atAll);

        return $this;
    }

    /**
     * @param $title
     * @param $text
     * @param $url
     *
     * @return false|\Psr\Http\Message\ResponseInterface
     */
    public function link($title, $text, $url, string $picUrl = '')
    {
        return $this->dingTalkService
            ->setLinkMessage($title, $text, $url, $picUrl)
            ->send();
    }

    /**
     * @param $title
     * @param $markdown
     *
     * @return false|\Psr\Http\Message\ResponseInterface
     */
    public function markdown($title, $markdown)
    {
        return $this->dingTalkService
            ->setMarkdownMessage($title, $markdown)
            ->send();
    }

    /**
     * @param $title
     * @param $markdown
     *
     * @return \HyperfGlory\AlertManager\Messages\ActionCard|\HyperfGlory\AlertManager\Messages\Message
     */
    public function actionCard($title, $markdown, int $hideAvatar = 0, int $btnOrientation = 0)
    {
        return $this->dingTalkService
            ->setActionCardMessage($title, $markdown, $hideAvatar, $btnOrientation);
    }

    /**
     * @return \HyperfGlory\AlertManager\Messages\FeedCard|\HyperfGlory\AlertManager\Messages\Message
     */
    public function feed()
    {
        return $this->dingTalkService
            ->setFeedCardMessage();
    }
}
