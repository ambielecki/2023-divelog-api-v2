<?php

namespace App\Models;

use App\Library\JsonResponseData;
use App\Library\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class DiveLog extends ApiModel
{
    use HasFactory;

    protected $guarded = ['id', 'user_id'];
    protected $hidden = ['user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function addAuthorization(Builder $query): Builder {
        $user = auth()->user();

        return $query->where('user_id', $user->id);
    }

    public function getMaxDiveNumberResponse(Request $request): JsonResponse
    {
        $query = self::newQuery();
        $query = $this->addAuthorization($query);

        return response()->json(JsonResponseData::formatData(
            $request,
            "",
            Message::MESSAGE_OK,
            ['max_dive_number' => $query->max('dive_number')],
        ));
    }
}
