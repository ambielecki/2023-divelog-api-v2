<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tag\TagIndexRequest;
use App\Library\JsonResponseData;
use App\Library\Message;
use App\Models\Tag;

class TagController extends Controller
{
    public function getIndex(TagIndexRequest $request) {
        $tag = new Tag();

        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            ['tags' => $tag->getList($request)],
        ));
    }
}
