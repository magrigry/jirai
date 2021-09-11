<?php

namespace Azuriom\Plugin\Jirai\Providers;

use Azuriom\Plugin\Jirai\Listeners\Notify;
use Azuriom\Plugin\Jirai\Listeners\SendWebhook;
use Azuriom\Plugin\Jirai\Listeners\WriteAutomaticMessage;


class EventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    protected $subscribe = [
        SendWebhook::class,
        WriteAutomaticMessage::class,
        Notify::class
    ];
}
