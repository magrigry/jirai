<?php

namespace Azuriom\Plugin\Jirai\Models;


use Azuriom\Models\User;
use Azuriom\Plugin\Jirai\Events\IssueClosedEvent;
use Azuriom\Plugin\Jirai\Events\IssueReopenedEvent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Issue
 *
 * @property int $id
 * @property string $message
 * @property boolean closed
 * @property string type
 * @property string title
 * @property int user_id
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

    public function getTitleWithTags(): string
    {
        $tags = '';
        foreach ($this->jiraiTags as $tag) {
            $tags .= sprintf("[%s]", $tag->name);
        }

        return $this->title . ' ' . $tags;
    }

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
        if (!$this->closed) {
            $this->closed = true;
            $this->save();
        }

        event($e = new IssueClosedEvent($this));
        return $e->getClosedMessage();
    }

    public function open()
    {
        if ($this->closed) {
            $this->closed = false;
            $this->save();
        }

        event($e = new IssueReopenedEvent($this));
        return $e->getOpenMessage();
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
