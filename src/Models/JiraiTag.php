<?php

namespace Azuriom\Plugin\Jirai\Models;


use Azuriom\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Tag
 *
 * @property integer $id
 * @property string $name
 * @property string $color
 * @property Collection<Role> $roles
 *
 * @package Azuriom\Plugin\Jirai\Models
 */
class JiraiTag extends Model
{

    protected $fillable = ['name', 'color'];

    public function jiraiIssues() {
        return $this->belongsToMany(JiraiIssue::class);
    }

    /**
     * Roles that can use the tag
     */
    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public static function getTagsForRole() {
        $tags = JiraiTag::with('roles')->get();

        foreach ($tags as $key => $tag) {
            if (!$tag->roles->contains('id', Auth::user()->role->id)) {
                $tags->forget($key);
            }
        }

        return $tags;
    }
}
