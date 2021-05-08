<?php

declare(strict_types=1);
/**
 * This file is part of Task-Schedule.
 *
 * @license  https://github.com/Hyperf-Glory/Task-Schedule/main/LICENSE
 */
namespace HyperfGlory\AlertManager;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Utils\ApplicationContext;
use HyperfGlory\AlertManager\Http\Client;
use HyperfGlory\AlertManager\Messages\ActionCard;
use HyperfGlory\AlertManager\Messages\FeedCard;
use HyperfGlory\AlertManager\Messages\Link;
use HyperfGlory\AlertManager\Messages\Markdown;
use HyperfGlory\AlertManager\Messages\Message;
use HyperfGlory\AlertManager\Messages\Text;
use Throwable;

class DingTalkService
{
    protected $config;

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var array
     */
    protected $mobiles = [];

    /**
     * @var bool
     */
    protected $atAll = false;

    /**
     * @var Client
     */
    protected $client;

    public function __construct($config, Client $client = null)
    {
        $this->config = $config;
        $this->setTextMessage('null');

        if ($client !== null) {
            $this->client = $client;

            return;
        }
        $this->client = $this->createClient($config);
    }

    public function setMessage(Message $message): void
    {
        $this->message = $message;
    }

    public function getMessage(): array
    {
        return $this->message->getMessage();
    }

    public function setAt(array $mobiles = [], bool $atAll = false): void
    {
        $this->mobiles = $mobiles;
        $this->atAll = $atAll;
        if ($this->message) {
            $this->message->sendAt($mobiles, $atAll);
        }
    }

    /**
     * @param $content
     *
     * @return $this
     */
    public function setTextMessage($content): self
    {
        $this->message = new Text($content);
        $this->message->sendAt($this->mobiles, $this->atAll);

        return $this;
    }

    /**
     * @param $title
     * @param $text
     * @param $messageUrl
     *
     * @return $this
     */
    public function setLinkMessage($title, $text, $messageUrl, string $picUrl = ''): self
    {
        $this->message = new Link($title, $text, $messageUrl, $picUrl);
        $this->message->sendAt($this->mobiles, $this->atAll);

        return $this;
    }

    /**
     * @param $title
     * @param $markdown
     *
     * @return $this
     */
    public function setMarkdownMessage($title, $markdown): self
    {
        $this->message = new Markdown($title, $markdown);
        $this->message->sendAt($this->mobiles, $this->atAll);

        return $this;
    }

    /**
     * @param $title
     * @param $markdown
     *
     * @return ActionCard|Message
     */
    public function setActionCardMessage($title, $markdown, int $hideAvatar = 0, int $btnOrientation = 0)
    {
        $this->message = new ActionCard($this, $title, $markdown, $hideAvatar, $btnOrientation);
        $this->message->sendAt($this->mobiles, $this->atAll);

        return $this->message;
    }

    /**
     * @return FeedCard|Message
     */
    public function setFeedCardMessage()
    {
        $this->message = new FeedCard($this);
        $this->message->sendAt($this->mobiles, $this->atAll);

        return $this->message;
    }

    /**
     * @return false|\Psr\Http\Message\ResponseInterface
     */
    public function send()
    {
        if (! $this->config['enabled']) {
            return false;
        }
        try {
            return $this->client->send($this->message->getBody());
        } catch (Throwable $e) {
            ApplicationContext::getContainer()->get(StdoutLoggerInterface::class)->error(format_throwable($e));

            return false;
        }
    }

    /**
     * @param $config
     */
    protected function createClient($config): Client
    {
        return new Client($config);
    }
}
