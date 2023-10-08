<?php

namespace App\Http\Controllers;

use App\Http\Requests\Page\HomePageEditRequest;
use App\Library\JsonResponseData;
use App\Library\Message;
use App\Models\Pages\HomePage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller {
    public function getPage(Request $request, $id): JsonResponse {
        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            [

            ]
        ));
    }

    public function getHomePage(Request $request): JsonResponse {
        $home_page = HomePage::query()
            ->where('is_active', 1)
            ->orderBy('revision', 'DESC')
            ->first();

        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            ['home_page' => $home_page],
        ));
    }

    public function postHomePage(HomePageEditRequest $request): JsonResponse {
        $home_page = HomePage::setHomePage($request->all());

        return response()->json(JsonResponseData::formatData(
            $request,
            'Home Page Updated',
            Message::MESSAGE_OK,
            ['home_page' => $home_page],
        ));
    }
}
