<?php

namespace App\Http\Controllers;

use App\Http\Requests\Image\ImageCreateRequest;
use App\Http\Requests\Image\ImageIndexRequest;
use App\Library\JsonResponseData;
use App\Library\Message;
use App\Models\Image;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    public function getIndex(ImageIndexRequest $request):  JsonResponse {
        $image = new Image();

        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            $image->getPaginatedResults($request),
        ));
    }

    public function postUpload(ImageCreateRequest $request): JsonResponse {
        $image = Image::createImage($request);
        if ($request->input('tags')) {
            $tag_ids = Tag::getListOfIds(explode(',', $request->input('tags')));
            $image->tags()->sync($tag_ids);
        }

        if ($image) {
            return response()->json(JsonResponseData::formatData(
                $request,
                '',
                Message::MESSAGE_OK,
                ['image_id' => $image->id],
            ));
        }

        return response()->json(JsonResponseData::formatData(
            $request,
            'Something Went Wrong, Please Try Again Later',
            Message::MESSAGE_ERROR,
            [],
        ), 500);
    }
}
