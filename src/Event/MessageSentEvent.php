<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;
use App\Entity\Message;

/**
 * The message.sent event is dispatched each time a user sends message
 * to another user.
 */
class MessageSentEvent extends Event
{
    public const NAME = 'message.sent';

    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}