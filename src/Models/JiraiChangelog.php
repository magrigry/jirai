<?php

namespace Azuriom\Plugin\Jirai\Models;


use Azuriom\Plugin\DiscordAuth\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JiraiChangelog
 *
 * @property string message
 * @property string description
 * @property integer user_id
 *
 * @package Azuriom\Plugin\Jirai\Models
 */
class JiraiChangelog extends Model
{

    protected $fillable = ['message', 'description', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
