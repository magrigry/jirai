<?php

namespace Azuriom\Plugin\Jirai\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Jirai\Models\Setting;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;

class SettingsController extends Controller
{



    /**
     * Display the discord-auth settings page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show()
    {
        return view('jirai::admin.settings', [
            'settings' => Setting::getSettings(),
        ]);
    }

    public function save(Request $request)
    {

        $validated = $this->validate($request, Setting::getRules());

        \Azuriom\Models\Setting::updateSettings(Setting::prefixWithDbKey($validated));

        return redirect()->route('jirai.admin.settings')->with('success', trans('admin.settings.status.updated'));
    }
}
