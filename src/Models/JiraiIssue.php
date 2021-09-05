<?php

namespace Azuriom\Plugin\Jirai\Models;


use Azuriom\Models\User;
use Azuriom\Plugin\Jirai\Notifier\DiscordWebhook;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

/**
 * Class Issue
 *
 * @property int $id
 * @property string $message
 * @property boolean closed
 * @property string type
 * @property string title
 *
 * @property User $user
 * @property Collection<JiraiMessage> $messages
 * @property Collection<JiraiTag> $jiraiTags
 *
 * @package Azuriom\Plugin\Jirai\Models
 */
class JiraiIssue extends Model
{

    public const TYPE_BUG = 'issue';
    public const TYPE_SUGGESTION = 'suggestion';

    public const TYPES = [
        self::TYPE_BUG => 'bugs',
        self::TYPE_SUGGESTION => 'suggestions'
    ];

    protected $fillable = ['title', 'message', 'type', 'closed', 'user_id'];

    public function messages()
    {
        return $this->hasMany(JiraiMessage::class);
    }

    public function jiraiTags()
    {
        return $this->belongsToMany(JiraiTag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function close()
    {
        $type = JiraiIssue::TYPES[$this->type];
        $title = $this->title;
        $id = $this->id;

        $this->closed = true;
        $this->save();

        $closeMsg = Lang::get('jirai::messages.issue_closed', [
            'id' => $id,
            'type' => $type,
            'title' => $title,
            'url' => route('jirai.issues.show', $id),
        ]);

        DiscordWebhook::sendWebhook(
            $this->type == JiraiIssue::TYPE_SUGGESTION
                ? Setting::getSetting(Setting::SETTING_DISCORD_WEB_HOOK_FOR_SUGGESTIONS)->getValue()
                : Setting::getSetting(Setting::SETTING_DISCORD_WEB_HOOK_FOR_BUGS)->getValue(),
            $this->title,
            $closeMsg,
            route('jirai.issues.show', $this),
            '15548997',
            false,
            $this->getContributors()
        );

        $message = new JiraiMessage();
        $message->message = $closeMsg;
        $message->user_id = Auth::id();
        $message->jirai_issue_id = $this->id;
        $message->save();

        return $closeMsg;
    }

    public function open()
    {
        $type = JiraiIssue::TYPES[$this->type];
        $title = $this->title;
        $id = $this->id;

        $this->closed = false;
        $this->save();

        $openMsg = Lang::get('jirai::messages.issue_reopened', [
            'id' => $id,
            'type' => $type,
            'title' => $title,
            'url' => route('jirai.issues.show', $id),
        ]);

        DiscordWebhook::sendWebhook(
            $this->type == JiraiIssue::TYPE_SUGGESTION
                ? Setting::getSetting(Setting::SETTING_DISCORD_WEB_HOOK_FOR_SUGGESTIONS)->getValue()
                : Setting::getSetting(Setting::SETTING_DISCORD_WEB_HOOK_FOR_BUGS)->getValue(),
            $this->title,
            $openMsg,
            route('jirai.issues.show', $this),
            '3066993',
            false,
            $this->getContributors()
        );

        $message = new JiraiMessage();
        $message->message = $openMsg;
        $message->user_id = Auth::id();
        $message->jirai_issue_id = $this->id;
        $message->save();

        return $openMsg;
    }

    /**
     * Get an array of user_id of people who contributed to the issue
     *
     * @var string $but This user id will be removed from the returned array
     * @return array of users_id
     */
    public function getContributors(): array
    {
        $contributors = [];

        if (Auth::id() != $this->user_id) {
            $contributors[] = $this->user_id;
        }

        foreach ($this->messages as $message) {
            if (Auth::id() != $message->user_id) {
                $contributors[] = $message->user_id;
            }
        }

        return array_unique($contributors);
    }

}
