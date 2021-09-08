<?php

namespace Azuriom\Plugin\Jirai\Models;


use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 *
 * @property string message
 *
 * @property integer jirai_issue_id
 * @property integer user_id
 *
 * @property JiraiIssue $jiraiIssue
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package Azuriom\Plugin\Jirai\Models
 */
class JiraiMessage extends Model
{

    protected $fillable = ['jirai_issue_id', 'message', 'user_id'];

    public function jiraiIssue() {
        return $this->belongsTo(JiraiIssue::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function referencedChangelog() {
        return $this->belongsTo(JiraiChangelog::class, 'referenced_jirai_changelog_id');
    }
}
