<?php

namespace Azuriom\Plugin\Jirai\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Jirai\Models\JiraiIssue;
use Azuriom\Plugin\Jirai\Models\JiraiMessage;
use Azuriom\Plugin\Jirai\Models\Setting;
use Azuriom\Plugin\Jirai\Notifier\DiscordWebhook;
use Azuriom\Plugin\Jirai\Requests\JiraiMessageRequest;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function store(JiraiMessageRequest $request)
    {
        $this->middleware('auth');
        $this->userHasPostPermission();

        $message = JiraiMessage::create($request->validated() + ['user_id' => Auth::user()->id]);

        DiscordWebhook::sendWebhook(
            $message->jiraiIssue->type == JiraiIssue::TYPE_SUGGESTION
                ? Setting::getSetting(Setting::SETTING_DISCORD_WEB_HOOK_FOR_SUGGESTIONS)->getValue()
                : Setting::getSetting(Setting::SETTING_DISCORD_WEB_HOOK_FOR_BUGS)->getValue(),
            $message->jiraiIssue->title,
            $message->message,
            route('jirai.issues.show', $message->jiraiIssue),
            '9937374',
            true
        );

        return redirect()->back();
    }

    public function edit(JiraiMessage $message)
    {
        $this->middleware('auth');
        $this->userHasEditPermission($message);
        return view('jirai::message.edit', ['message' => $message]);
    }

    public function update(JiraiMessageRequest $request, JiraiMessage $message)
    {
        $this->middleware('auth');
        $this->userHasEditPermission($message);
        $message->update($request->validated());
        return redirect()->route('jirai.issues.show', ['issue' => $message->jirai_issue_id])->with('success', trans('jirai::messages.done'));
    }

    public function destroy(JiraiMessage $message)
    {
        $this->middleware('auth');
        $this->userHasDeletePermission($message);

        $message->delete();

        return redirect()->route('jirai.issues.show', ['issue' => $message->jirai_issue_id])->with('success', trans('jirai::messages.done'));
    }

    private function userHasEditPermission($message)
    {
        if (($message->user_id != Auth::id() && !Auth::user()->hasPermission('jirai.messages.edit.others'))
            || Auth::user()->hasPermission('jirai.messages.edit.self')
        ) {
            abort(403);
        }
    }

    private function userHasPostPermission() {
        if (!Auth::user()->hasPermission('jirai.messages.post')) {
            abort(403);
        }
    }

    private function userHasDeletePermission($message)
    {
        if (($message->user_id != Auth::id() && !Auth::user()->hasPermission('jirai.messages.delete.others'))
            || Auth::user()->hasPermission('jirai.messages.delete.self')
        ) {
            abort(403);
        }
    }
}
