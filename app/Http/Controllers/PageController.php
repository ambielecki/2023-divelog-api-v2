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
        $first_home_page = HomePage::query()
            ->where('is_active', 1)
            ->orderBy('revision', 'ASC')
            ->first();
        $data = [
            'parent_id' => null,
            'revision' => 1
        ];

        if ($first_home_page) {
            $last_revision = (int) HomePage::query()->max('revision');
            $data = [
                'parent_id' => $first_home_page->id,
                'revision' => $last_revision + 1
            ];
        }

        DB::beginTransaction();

        HomePage::query()->update(['is_active' => 0]);

        $home_page = HomePage::create(array_merge([
            'is_active' => true,
            'content' => [
                'content' => $request->input('page.content.content'),
                'title' => $request->input('page.content.title'),
                'hero_image' => $request->input('hero_image'),
                'carousel_images'=> $request->input('carousel_images'),
            ]
        ], $data));

        DB::commit();

        return response()->json(JsonResponseData::formatData(
            $request,
            'Home Page Updated',
            Message::MESSAGE_OK,
            ['home_page' => $home_page],
        ));
    }
}
