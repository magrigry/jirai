<?php

namespace Azuriom\Plugin\Jirai\Listeners;


use Azuriom\Plugin\Jirai\Events\ChangelogPostedEvent;
use Azuriom\Plugin\Jirai\Events\IssueClosedEvent;
use Azuriom\Plugin\Jirai\Events\IssueCreatedEvent;
use Azuriom\Plugin\Jirai\Events\IssueUpdatedEvent;
use Azuriom\Plugin\Jirai\Events\IssueReopenedEvent;
use Azuriom\Plugin\Jirai\Events\MessagePostedEvent;
use Azuriom\Plugin\Jirai\Models\Setting;
use Azuriom\Support\Discord\DiscordWebhook;
use Azuriom\Support\Discord\Embed;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SendWebhook
{

    public function onIssueCreated(IssueCreatedEvent $e)
    {
        self::sendWebhook(
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
            self::sendWebhook(
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
        self::sendWebhook(
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
        self::sendWebhook(
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
        self::sendWebhook(
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
        self::sendWebhook(
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

    private static function resizeContent($content, $url, $padding) {

        if (strlen($content) > 4000 - $padding) {
            $content = substr($content, 0, 4000);
            $content .= "\n\n" . trans('jirai::messages.click_to_see_more', ['url' => $url]);
        }

        return $content;
    }

    private static function replaceListCharacter($content) {
        $pattern = '/(\r?\n)( *)\*/';
        $replace = '$1$2•';
        return preg_replace($pattern, $replace, $content);
    }

    private static function protectFromMention($text) {
        return str_replace('@', '\@', $text);
    }

    private static function sendWebhook($webhook, $title, $content, $url, $color, $protectFromMention = true, $usersToMention = [])
    {
        $mentions = '';
        if (class_exists('\Azuriom\Plugin\DiscordAuth\Models\Discord')) {
            $discords = \Azuriom\Plugin\DiscordAuth\Models\Discord::whereIn('user_id', $usersToMention)->get();
            foreach ($discords as $discord) {
                $mentions .= sprintf('<@%s> ', $discord->discord_id);
            }
        }

        if (empty($webhook)) {
            return null;
        }

        $content = self::resizeContent($content, $url, strlen($mentions));
        $content = self::replaceListCharacter($content);

        if ($protectFromMention) {
            $title = self::protectFromMention($title);
            $content = self::protectFromMention($content);
        }

        $embed = new Embed();
        $embed->title($title);
        $embed->url($url);
        $embed->description($content);
        $embed->color($color);

        try {
            DiscordWebhook::create()
                ->content($mentions)
                ->username(Auth::user()->name)
                ->avatarUrl(Auth::user()->getAvatar())
                ->addEmbed($embed)
                ->send($webhook);
        } catch (HttpClientException $e) {
            Log::alert($e->getMessage());
        }
    }

}
