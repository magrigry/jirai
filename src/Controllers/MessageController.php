<?php

namespace Azuriom\Plugin\Jirai\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Jirai\Events\MessagePostedEvent;
use Azuriom\Plugin\Jirai\Models\JiraiMessage;
use Azuriom\Plugin\Jirai\Models\Permission;
use Azuriom\Plugin\Jirai\Requests\JiraiMessageRequest;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function store(JiraiMessageRequest $request)
    {
        $this->middleware('auth');
        $this->userHasPostPermission();

        $message = JiraiMessage::create($request->validated() + ['user_id' => Auth::id()]);

        event(new MessagePostedEvent($message));

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
        if (!Permission::hasJiraiMessageEditPermission($message->user_id)) {
            abort(403);
        }
    }

    private function userHasPostPermission()
    {
        if (!Auth::user()->hasPermission('jirai.message.post')) {
            abort(403);
        }
    }

    private function userHasDeletePermission($message)
    {
        if (!Permission::hasJiraiMessageDeletePermission($message->user_id)) {
            abort(403);
        }
    }
}
