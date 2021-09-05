<?php

namespace Azuriom\Plugin\Jirai\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Jirai\Models\JiraiChangelog;
use Azuriom\Plugin\Jirai\Models\JiraiIssue;
use Azuriom\Plugin\Jirai\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;


class JiraiHomeController extends Controller
{
    /**
     * Show the home plugin page.
     *
     * @return Application|Factory|View
     */
    public function index()
    {

        return view('jirai::index',
            [
                'suggestions' => JiraiIssue::with('jiraiTags')
                    ->with('user')
                    ->where('type', JiraiIssue::TYPE_SUGGESTION)
                    ->orderByDesc('id')
                    ->paginate(Setting::getSetting(Setting::SETTING_ISSUES_PER_PAGES)->getValue(), ['*'], JiraiIssue::TYPE_SUGGESTION),

                'bugs' => JiraiIssue::with('jiraiTags')
                    ->with('user')
                    ->where('type', JiraiIssue::TYPE_BUG)
                    ->orderByDesc('id')
                    ->paginate(Setting::getSetting(Setting::SETTING_ISSUES_PER_PAGES)->getValue(), ['*'], JiraiIssue::TYPE_BUG),

                'changelogs' => JiraiChangelog::orderByDesc('id')
                    ->with('user')
                    ->paginate(Setting::getSetting(Setting::SETTING_CHANGELOGS_PER_PAGES)->getValue(), ['*'], 'changelogs')
            ]
        );
    }
}
