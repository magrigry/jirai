<?php

namespace Azuriom\Plugin\Jirai\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Role;
use Azuriom\Plugin\Jirai\Models\JiraiTag;
use Azuriom\Plugin\Jirai\Requests\JiraiTagRequest;

class TagsController extends Controller
{

    public function index() {
        return view('jirai::admin.tag.index', ['tags' => JiraiTag::all()]);
    }

    public function create()
    {
        return view('jirai::admin.tag.create', ['roles' => Role::all()]);
    }

    public function edit(JiraiTag $tag)
    {
        return view('jirai::admin.tag.edit', ['roles' => Role::all(), 'tag' => $tag]);
    }

    public function store(JiraiTagRequest $request)
    {

        $tag = JiraiTag::create($request->validated());

        if ($request->has('roles')) {
            $tag->roles()->sync($request->get('roles'));
        }

        return redirect()->route('jirai.admin.tags.index');
    }

    public function update(JiraiTagRequest $request, JiraiTag $tag)
    {
        $tag->update($request->validated());

        if ($request->has('roles')) {
            $tag->roles()->sync($request->get('roles'));
        }

        return redirect()->route('jirai.admin.tags.index');
    }

    public function destroy(JiraiTag $tag)
    {
        $tag->delete();
    }
}
