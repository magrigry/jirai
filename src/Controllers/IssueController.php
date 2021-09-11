<?php

namespace Azuriom\Plugin\Jirai\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Jirai\Events\IssueCreatedEvent;
use Azuriom\Plugin\Jirai\Events\IssueUpdatedEvent;
use Azuriom\Plugin\Jirai\Models\JiraiIssue;
use Azuriom\Plugin\Jirai\Models\JiraiTag;
use Azuriom\Plugin\Jirai\Models\Permission;
use Azuriom\Plugin\Jirai\Requests\JiraiIssueRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{

    public function show(JiraiIssue $issue) {
        $issue->load('user', 'messages', 'messages.user', 'messages.user.role');
        $messages = $issue->messages()->get();
        return view('jirai::issue.show', ['issue' => $issue, 'messages' => $messages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(Request $request)
    {
        $this->middleware('auth');
        $this->checkPostPermission();

        return view('jirai::issue.create',
            [
                'preSelectedType' => $request->get('type', null),
                'tags' => JiraiTag::getTagsForRole(),
                'types' => JiraiIssue::TYPES
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param JiraiIssueRequest $request
     * @return RedirectResponse
     */
    public function store(JiraiIssueRequest $request)
    {
        $this->middleware('auth');
        $this->checkPostPermission();

        $issue = JiraiIssue::create($request->validated() + ['user_id' => Auth::user()->id]);

        $issue->jiraiTags()->sync($request->input('tags'));

        event(new IssueCreatedEvent($issue));

        return redirect()->route('jirai.home')->with('success', trans('jirai::messages.done'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param JiraiIssue $issue
     * @return Application|Factory|View
     */
    public function edit(JiraiIssue $issue)
    {
        $this->middleware('auth');
        $this->checkEditPermission($issue);

        return view('jirai::issue.edit',
            [
                'tags' => JiraiTag::getTagsForRole(),
                'issue' => $issue,
                'types' => JiraiIssue::TYPES
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param JiraiIssueRequest $request
     * @param JiraiIssue $issue
     * @return RedirectResponse
     */
    public function update(JiraiIssueRequest $request, JiraiIssue $issue)
    {
        $this->middleware('auth');
        $this->checkEditPermission($issue);
        $oldIssue = $issue->replicate();

        $issue->update($request->validated());


        event(new IssueUpdatedEvent($issue, $oldIssue));

        $issue->jiraiTags()->sync($request->input('tags'));

        if ($oldIssue->closed == false && $request->get('closed') == true) {
            $issue->close();
        }

        if ($oldIssue->closed == true && $request->get('closed') == false) {
            $issue->open();
        }

        return redirect()->route('jirai.issues.show', $issue)->with('success', trans('jirai::messages.done'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param JiraiIssue $issue
     * @return RedirectResponse
     */
    public function destroy(JiraiIssue $issue)
    {
        $this->middleware('auth');
        $this->userHasDeletePermission($issue);

        $issue->delete();

        return redirect()->route('jirai.home')->with('success', trans('jirai::messages.done'));
    }

    private function checkEditPermission($issue)
    {
        if (!Permission::hasIssueEditPermission($issue->user_id)) {
            abort(403);
        }
    }

    private function checkPostPermission() {
        if (!Auth::user()->hasPermission('jirai.issue.post')) {
            abort(403);
        }
    }

    private function userHasDeletePermission($issue)
    {
        if (!Permission::hasIssueDeletePermission($issue->user_id)) {
            abort(403);
        }
    }
}
