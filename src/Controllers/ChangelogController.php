<?php

namespace Azuriom\Plugin\Jirai\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Jirai\Events\ChangelogPostedEvent;
use Azuriom\Plugin\Jirai\Models\JiraiChangelog;
use Azuriom\Plugin\Jirai\Models\JiraiIssue;
use Azuriom\Plugin\Jirai\Models\JiraiMessage;
use Azuriom\Plugin\Jirai\Models\Permission;
use Azuriom\Plugin\Jirai\Models\Setting;
use Azuriom\Plugin\Jirai\Requests\JiraiChangelogRequest;
use Illuminate\Support\Facades\Auth;

class ChangelogController extends Controller
{
    public function create() {
        return view('jirai::changelog.create', ['issues' => JiraiIssue::where('closed', 0)->orderBy('id')->get()]);
    }

    public function store(JiraiChangelogRequest $request) {

        $this->middleware('auth');

        if (!Permission::hasJiraiChangelogPostPermission()) {
            abort(403);
        }

        $data = $request->validated();
        $data['message'] .= $this->closeRelatedIssues($request->get('issues'));
        $data['user_id'] = Auth::id();

        $changelog = JiraiChangelog::create($data);
        $this->addReferencesToMessages($changelog, $request->get('issues'));

        return redirect(route('jirai.changelogs.show', $changelog->id));
    }

    public function show(JiraiChangelog $changelog)
    {
        $changelog->user();
        return view('jirai::changelog.show', ['changelog' => $changelog]);
    }

    public function edit(JiraiChangelog $changelog)
    {
        $this->middleware('auth');

        if (!Permission::hasJiraiChangelogPostPermission()) {
            abort(403);
        }

        return view('jirai::changelog.edit', ['changelog' => $changelog, 'issues' => JiraiIssue::where('closed', 0)->orderBy('id')->get()]);
    }

    public function update(JiraiChangelogRequest $request, JiraiChangelog $changelog)
    {
        $this->middleware('auth');

        if (!Permission::hasJiraiChangelogPostPermission()) {
            abort(403);
        }

        $data = $request->validated();
        $data['message'] .= $this->closeRelatedIssues($request->get('issues'));

        $changelog->update($data);

        $this->addReferencesToMessages($changelog, $request->get('issues'));

        event(new ChangelogPostedEvent($changelog));

        return redirect()->route('jirai.changelogs.show', $changelog)->with('success', trans('jirai::messages.done'));
    }

    public function destroy(JiraiChangelog $changelog) {
        $this->middleware('auth');

        if (!Permission::hasJiraiChangelogPostPermission()) {
            abort(403);
        }

        $changelog->delete();

        return redirect()->route('jirai.home')->with('success', trans('jirai::messages.done'));

    }

    /**
     * @param $issuesIds
     * @return string
     *
     * @toto Refactor for a more optimized and readable way
     */
    private function closeRelatedIssues($issuesIds)
    {

        if ($issuesIds == null) {
            return null;
        }

        $issuesClosedMsg = '';

        foreach ($issuesIds as $issue) {
            $issuesClosedMsg .= "\n\n" . JiraiIssue::findOrFail($issue)->close() . "\n\n";
        }

        return $issuesClosedMsg;
    }

    /**
     * @param $changelog
     * @param $issuesIds
     *
     * @toto Refactor for a more optimized and readable way
     */
    private function addReferencesToMessages($changelog, $issuesIds)
    {
        if ($issuesIds == null) {
            return null;
        }

        foreach ($issuesIds as $issue) {

            $message = new JiraiMessage();
            $message->jirai_issue_id = $issue;
            $message->user_id = Auth::id();
            $message->referenced_jirai_changelog_id = $changelog->id;
            $message->save();

        }
    }
}
