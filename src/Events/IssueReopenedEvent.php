<?php

namespace Azuriom\Plugin\Jirai\Events;


use Azuriom\Plugin\Jirai\Models\JiraiIssue;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Lang;

/**
 *
 * Event triggered when an issue has been re-opened
 *
 * @package Azuriom\Plugin\Jirai\Events
 */
class IssueReopenedEvent
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $openMessage = '';

    /**
     * Issue in database after it has been saved
     * @var JiraiIssue
     */
    public $issue;

    public function __construct(JiraiIssue $issue)
    {
        $this->issue = $issue;

        $this->openMessage = Lang::get('jirai::messages.issue_reopened', [
            'id' => $this->issue->id,
            'type' => $this->issue->type,
            'title' => $this->issue->title,
            'url' => route('jirai.issues.show', $this->issue->id),
        ]);
    }

    /**
     * @return mixed
     */
    public function getOpenMessage()
    {
        return $this->openMessage;
    }
}
