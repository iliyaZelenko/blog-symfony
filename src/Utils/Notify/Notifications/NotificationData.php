<?php

namespace App\Utils\Notify\Notifications;

class NotificationData
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $HTMLBody;

    /**
     * NotificationData constructor.
     *
     * @param string $title
     * @param string $text
     */
    public function __construct(string $title = null, string $text = null)
    {
        $this->title = $title;
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getHTMLBody(): string
    {
        return $this->HTMLBody;
    }

    /**
     * @param string $HTMLBody
     */
    public function setHTMLBody(string $HTMLBody): void
    {
        $this->HTMLBody = $HTMLBody;
    }
}
