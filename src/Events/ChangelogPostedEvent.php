<?php

namespace Azuriom\Plugin\Jirai\Events;


use Azuriom\Plugin\Jirai\Models\JiraiChangelog;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 *
 * Event triggered when a changelog is posted
 *
 * @package Azuriom\Plugin\Jirai\Events
 */
class ChangelogPostedEvent
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $closedMessage = '';

    /**
     * @var JiraiChangelog
     */
    public $changelog;

    public function __construct(JiraiChangelog $changelog)
    {
        $this->changelog = $changelog;
    }
}
