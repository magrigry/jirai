<?php

namespace Azuriom\Plugin\Jirai\Events;


use Azuriom\Plugin\Jirai\Models\JiraiIssue;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 *
 * Event triggered when an issue has been updated
 *
 * @package Azuriom\Plugin\Jirai\Events
 */
class IssueUpdatedEvent
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Issue in database after it has been saved
     * @var JiraiIssue
     */
    public $issue;

    /**
     * Issue before the update
     * @var JiraiIssue
     */
    public $oldIssue;

    public function __construct(JiraiIssue $issue, JiraiIssue $oldIssue)
    {
        $this->issue = $issue;
        $this->oldIssue = $oldIssue;
    }

}
