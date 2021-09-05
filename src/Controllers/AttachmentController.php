<?php

namespace Azuriom\Plugin\Jirai\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Http\Requests\AttachmentRequest;

class AttachmentController extends Controller
{

    public function storeAttachment(AttachmentRequest $request)
    {
        $filename = sha1(microtime());
        $url = $request->file('file')->storeAs('public/jirai/', $filename);
        return response()->json(['location' => url('storage/jirai/' . $filename)]);
    }
}
