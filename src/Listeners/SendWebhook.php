<?php

namespace Azuriom\Plugin\Jirai\Listeners;


use Azuriom\Plugin\Jirai\Events\ChangelogPostedEvent;
use Azuriom\Plugin\Jirai\Events\IssueClosedEvent;
use Azuriom\Plugin\Jirai\Events\IssueCreatedEvent;
use Azuriom\Plugin\Jirai\Events\IssueUpdatedEvent;
use Azuriom\Plugin\Jirai\Events\IssueReopenedEvent;
use Azuriom\Plugin\Jirai\Events\MessagePostedEvent;
use Azuriom\Plugin\Jirai\Models\Setting;
use Azuriom\Plugin\Jirai\Notifier\DiscordWebhook;

class SendWebhook
{

    public function onIssueCreated(IssueCreatedEvent $e)
    {
        DiscordWebhook::sendWebhook(
            Setting::getWebhookUrlFor($e->issue->type),
            $e->issue->title,
            $e->issue->message,
            route('jirai.issues.show', $e->issue),
            '9937374',
            true
        );
    }

    public function onIssueUpdated(IssueUpdatedEvent $e)
    {
        if ($e->oldIssue->message != $e->issue->message  || $e->oldIssue->title != $e->issue->title) {
            DiscordWebhook::sendWebhook(
                Setting::getWebhookUrlFor($e->issue->type),
                $e->issue->title,
                $e->issue->message,
                route('jirai.issues.show', $e->issue),
                '9937374',
                true
            );
        }
    }

    public function onIssueClosed(IssueClosedEvent $e)
    {
        DiscordWebhook::sendWebhook(
            Setting::getWebhookUrlFor($e->issue->type),
            $e->issue->title,
            $e->getClosedMessage(),
            route('jirai.issues.show', $e->issue),
            '15548997',
            false,
            $e->issue->getContributors()
        );
    }

    public function onIssueOpened(IssueReopenedEvent $e)
    {
        DiscordWebhook::sendWebhook(
            Setting::getWebhookUrlFor($e->issue->type),
            $e->issue->title,
            $e->getOpenMessage(),
            route('jirai.issues.show', $e->issue),
            '9937374',
            false,
            $e->issue->getContributors()
        );

    }

    public function onMessagePosted(MessagePostedEvent $e)
    {
        DiscordWebhook::sendWebhook(
            Setting::getWebhookUrlFor($e->message->jiraiIssue->type),
            $e->message->jiraiIssue->title,
            $e->message->message,
            route('jirai.issues.show', $e->message->jiraiIssue),
            '9937374',
            true,
            $e->message->jiraiIssue->getContributors()
        );
    }

    public function changelog(ChangelogPostedEvent $e) {
        DiscordWebhook::sendWebhook(
            Setting::getSetting(Setting::SETTING_DISCORD_WEB_HOOK_FOR_CHANGELOGS)->getValue(),
            $e->changelog->description,
            $e->changelog->message,
            route('jirai.changelogs.show', $e->changelog),
            '9937374',
            false
        );
    }

    public function subscribe()
    {
        return [
            IssueCreatedEvent::class => 'onIssueCreated',
            IssueClosedEvent::class => 'onIssueClosed',
            IssueReopenedEvent::class => 'onIssueOpened',
            IssueUpdatedEvent::class => 'onIssueUpdated',
            MessagePostedEvent::class => 'onMessagePosted',
            ChangelogPostedEvent::class => 'changelog',
        ];
    }

}
