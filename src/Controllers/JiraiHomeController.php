<?php

namespace Azuriom\Plugin\Jirai\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Jirai\Models\JiraiChangelog;
use Azuriom\Plugin\Jirai\Models\JiraiIssue;
use Azuriom\Plugin\Jirai\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class JiraiHomeController extends Controller
{
    /**
     * Show the home plugin page.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {

        return view('jirai::index',
            [
                'suggestions' => $this->query(JiraiIssue::TYPE_SUGGESTION)
                    ->paginate(Setting::getSetting(Setting::SETTING_ISSUES_PER_PAGES)->getValue(), ['*'], JiraiIssue::TYPE_SUGGESTION),

                'bugs' => $this->query(JiraiIssue::TYPE_BUG)
                    ->paginate(Setting::getSetting(Setting::SETTING_ISSUES_PER_PAGES)->getValue(), ['*'], JiraiIssue::TYPE_BUG),

                'changelogs' => JiraiChangelog::orderByDesc('id')
                    ->with('user')
                    ->paginate(Setting::getSetting(Setting::SETTING_CHANGELOGS_PER_PAGES)->getValue(), ['*'], 'changelogs')
            ]
        );
    }

    /**
     * @param $filterString
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function query($type): Builder
    {
        return  JiraiIssue::query()
            ->with('messages')
            ->with('messages.user')
            ->with('jiraiTags')
            ->with('user')
            ->orderByDesc('id')
            ->where('type', $type);
    }
}
