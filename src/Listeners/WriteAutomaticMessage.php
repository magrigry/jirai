<?php

namespace Azuriom\Plugin\Jirai\Listeners;

use Azuriom\Plugin\Jirai\Events\IssueClosedEvent;
use Azuriom\Plugin\Jirai\Events\IssueUpdatedEvent;
use Azuriom\Plugin\Jirai\Events\IssueReopenedEvent;
use Azuriom\Plugin\Jirai\Models\JiraiMessage;

use Illuminate\Support\Facades\Auth;

class WriteAutomaticMessage
{

    public function notifyTitleChange(IssueUpdatedEvent $e)
    {
        if ($e->issue->title != $e->oldIssue->title) {

            $message = new JiraiMessage();
            $message->message = trans('jirai::messages.has_changed_title', [
                'user' => $e->issue->user->name,
                'old_title' => $e->oldIssue->title,
                'new_title' => $e->issue->title,
            ]);
            $message->user_id = Auth::id();
            $message->jirai_issue_id = $e->issue->id;
            $message->save();

        }
    }

    public function notifyIssueClosed(IssueClosedEvent $e)
    {
        $message = new JiraiMessage();
        $message->message = $e->getClosedMessage();
        $message->user_id = Auth::id();
        $message->jirai_issue_id = $e->issue->id;
        $message->save();
    }

    public function notifyIssueOpened(IssueReopenedEvent $e)
    {
        $message = new JiraiMessage();
        $message->message = $e->getOpenMessage();
        $message->user_id = Auth::id();
        $message->jirai_issue_id = $e->issue->id;
        $message->save();
    }

    public function subscribe($events)
    {
        return [
            IssueUpdatedEvent::class => 'notifyTitleChange',
            IssueClosedEvent::class => 'notifyIssueClosed',
            IssueReopenedEvent::class => 'notifyIssueOpened',
        ];
    }
}
