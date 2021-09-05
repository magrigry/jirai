<?php

namespace Azuriom\Plugin\Jirai\Notifier;

use Azuriom\Support\Discord\Embed;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DiscordWebhook extends \Azuriom\Support\Discord\DiscordWebhook
{

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

    public static function sendWebhook($webhook, $title, $content, $url, $color, $protectFromMention = true, $usersToMention = [])
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
            self::create()
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
