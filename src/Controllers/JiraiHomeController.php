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
                'suggestions' => $this->filter(JiraiIssue::TYPE_SUGGESTION, $request->get('filter'))
                    ->paginate(Setting::getSetting(Setting::SETTING_ISSUES_PER_PAGES)->getValue(), ['*'], JiraiIssue::TYPE_SUGGESTION),

                'bugs' => $this->filter(JiraiIssue::TYPE_BUG, $request->get('filter'))
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
    private function filter($type, $filterString)
    {

        $query = JiraiIssue::query()
            ->with('messages')
            ->with('jiraiTags')
            ->with('user')
            ->orderByDesc('id')
            ->with('user')
            ->where('type', $type);

        if (empty($filterString)) {
            return $query;
        }

        $conditions = [];

        preg_match('/status:(close|open)/', $filterString, $status);

        if ($status[1] == 'open') {
            $conditions[] = ['closed', true];
        } elseif ($status[1] == 'close') {
            $conditions[] = ['closed', true];
        }

        preg_match_all('/tag:(\w+)/', $filterString, $tag);

        foreach ($tag[1] as $tag) {
            $conditions[] = ['jirai_tags.name', $tag];
        }

        $query->where(function ($query) use ($conditions) {

            foreach ($conditions as $key => $condition) {
                if ($key == 0) {
                    $query->where($condition[0], $condition[1]);
                    continue;
                }

                $query->orWhere($condition[0], $condition[1]);
            }
        });

        return $query;
    }
}
