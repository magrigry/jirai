<?php

namespace Azuriom\Plugin\Jirai\Models;


use Azuriom\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class Permission
{

    public static function registerPermissions() {
        \Azuriom\Models\Permission::registerPermissions([
            'jirai.message.post' => 'jirai::admin.permission.message-post',
            'jirai.message.edit.self' => 'jirai::admin.permission.message-edit-self',
            'jirai.message.edit.others' => 'jirai::admin.permission.message-edit-others',
            'jirai.message.delete.self' => 'jirai::admin.permission.message-delete-self',
            'jirai.message.delete.others' => 'jirai::admin.permission.message-delete-others',

            'jirai.issue.post' => 'jirai::admin.permission.issue-post',
            'jirai.issue.edit.self' => 'jirai::admin.permission.issue-edit-self',
            'jirai.issue.edit.others' => 'jirai::admin.permission.issue-edit-others',
            'jirai.issue.delete.self' => 'jirai::admin.permission.issue-delete-self',
            'jirai.issue.delete.others' => 'jirai::admin.permission.issue-delete-others',

            'jirai.changelog.post' => 'jirai::admin.permission.changelog-post',

            'jirai.admin.settings' => 'jirai::admin.permission.admin-settings',
        ]);
    }

    public static function registerInBlade() {
        Blade::if('hasJiraiIssueEditPermission', function ($userId) { return self::hasIssueEditPermission($userId); });
        Blade::if('hasJiraiIssueDeletePermission', function ($userId) { return self::hasIssueDeletePermission($userId); });
        Blade::if('hasJiraiMessageEditPermission', function ($userId) { return self::hasJiraiMessageEditPermission($userId); });
        Blade::if('hasJiraiMessageDeletePermission', function ($userId) { return self::hasJiraiMessageDeletePermission($userId); });
    }

    public static function hasJiraiMessageEditPermission($userId) {
        return (
            Auth::user() && ((Auth::user()->hasPermission('jirai.message.edit.self')
                    && $userId == Auth::id())
                || Auth::user()->hasPermission('jirai.message.edit.others'))
        );
    }

    public static function hasJiraiMessageDeletePermission($userId) {
        return (
            Auth::user() && ((Auth::user()->hasPermission('jirai.message.delete.self')
                    && $userId == Auth::id())
                || Auth::user()->hasPermission('jirai.message.delete.others'))
        );
    }

    public static function hasIssueEditPermission($userId) {
        return (
            Auth::user() && ((Auth::user()->hasPermission('jirai.issue.edit.self')
                    && $userId == Auth::id())
                || Auth::user()->hasPermission('jirai.issue.edit.others'))
        );
    }

    public static function hasIssueDeletePermission($userId) {
        return (
            Auth::user() && ((Auth::user()->hasPermission('jirai.issue.delete.self')
                && $userId == Auth::id())
            || Auth::user()->hasPermission('jirai.issue.delete.others'))
        );
    }

    public static function hasJiraiChangelogPostPermission() {
        return Auth::user()->hasPermission('jirai.changelog.post');
    }

}
