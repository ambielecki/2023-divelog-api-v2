<?php

namespace App\Http\Controllers;

use App\Http\Requests\Page\HomePageEditRequest;
use App\Library\JsonResponseData;
use App\Library\Message;
use App\Models\Pages\BlogPage;
use App\Models\Pages\HomePage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends Controller {
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

    public function getBlogPage(Request $request, string $slug): JsonResponse {
        $blog_page = BlogPage::query()
            ->where([
                ['slug', $slug],
                ['is_active', true],
            ])
            ->orderBy('revision', 'DESC')
            ->first();

        if ($blog_page) {
            return response()->json(JsonResponseData::formatData(
                $request,
                '',
                Message::MESSAGE_OK,
                ['blog_page' => $blog_page]
            ));
        }

        return response()->json(JsonResponseData::formatData(
            $request,
            'We could not find the blog you requested',
            Message::MESSAGE_WARNING,
            ['blog_page' => $blog_page]
        ), 404);
    }

    public function getBlogList(Request $request): JsonResponse {
        $blog = new BlogPage();

        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            $blog->getPaginatedResults($request),
        ));
    }

    public function postBlogPage(HomePageEditRequest $request): JsonResponse {
        $blog_page = BlogPage::createBlogEntry($request->all());

        return response()->json(JsonResponseData::formatData(
            $request,
            'Blog Entry Created',
            Message::MESSAGE_OK,
            ['blog' => $blog_page],
        ));
    }
}
