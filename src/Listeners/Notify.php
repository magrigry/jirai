<?php

namespace Azuriom\Plugin\Jirai\Listeners;


use Azuriom\Models\Notification;
use Azuriom\Plugin\Jirai\Events\IssueClosedEvent;
use Azuriom\Plugin\Jirai\Events\IssueReopenedEvent;
use Azuriom\Plugin\Jirai\Events\MessagePostedEvent;


class Notify
{

    public function onIssueClosed(IssueClosedEvent $e)
    {
        foreach ($e->issue->getContributors() as $user_id) {
            $notif = new Notification();
            $notif->user_id = $user_id;
            $notif->level = 'info';
            $notif->content = sprintf('#%s %s [%s]',
                $e->issue->id,
                substr($e->issue->title, 0, 50),
                trans('jirai::messages.open')
            );
            $notif->author_id = $e->issue->user_id;
            $notif->link = route('jirai.issues.show', $e->issue);
            $notif->save();
        }
    }

    public function onIssueOpened(IssueReopenedEvent $e)
    {
        foreach ($e->issue->getContributors() as $user_id) {
            $notif = new Notification();
            $notif->user_id = $user_id;
            $notif->level = 'info';
            $notif->content = sprintf('#%s %s [%s]',
                $e->issue->id,
                substr($e->issue->title, 0, 50),
                trans('jirai::messages.closed')
            );
            $notif->author_id = $e->issue->user_id;
            $notif->link = route('jirai.issues.show', $e->issue);
            $notif->save();
        }
    }

    public function onMessagePosted(MessagePostedEvent $e)
    {
        foreach ($e->message->jiraiIssue->getContributors() as $user_id) {

            $notif = new Notification();
            $notif->user_id = $user_id;
            $notif->level = 'info';
            $notif->content = substr($e->message->message, 0, 50) . ' [...]';
            $notif->author_id = $e->message->user_id;
            $notif->link = route('jirai.issues.show', $e->message->jiraiIssue);
            $notif->save();
        }
    }

    public function subscribe()
    {
        return [
            IssueClosedEvent::class => 'onIssueClosed',
            IssueReopenedEvent::class => 'onIssueOpened',
            MessagePostedEvent::class => 'onMessagePosted',
        ];
    }



}
