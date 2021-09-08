<?php

namespace Azuriom\Plugin\Jirai\Events;


use Azuriom\Plugin\Jirai\Models\JiraiMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 *
 * Event triggered when a message (not automatic) is posted
 *
 * @package Azuriom\Plugin\Jirai\Events
 */
class MessagePostedEvent
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Message that has been sent
     *
     * @var JiraiMessage
     */
    public $message;

    public function __construct(JiraiMessage $message)
    {
        $this->message = $message;
    }

}
