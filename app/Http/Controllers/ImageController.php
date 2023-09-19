<?php

namespace App\Http\Controllers;

use App\Http\Requests\Image\ImageCreateRequest;
use App\Library\JsonResponseData;
use App\Library\Message;
use App\Models\Image;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    public function postUpload(ImageCreateRequest $request): JsonResponse {
        $image_id = Image::createImage($request);

        if ($image_id) {
            return response()->json(JsonResponseData::formatData(
                $request,
                '',
                Message::MESSAGE_OK,
                ['image_id' => $image_id],
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
