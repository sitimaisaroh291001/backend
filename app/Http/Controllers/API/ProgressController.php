<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgressResource;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgressController extends Controller
{
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $progress = Progress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $request->lesson_id,
            ],
            [
                'progress_percentage' => $request->progress_percentage,
                'completed_at' => $request->progress_percentage == 100 ? now() : null,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Progress updated successfully',
            'data' => new ProgressResource($progress->load(['user', 'lesson']))
        ]);
    }

    public function getByUser($userId)
    {
        $progresses = Progress::where('user_id', $userId)->with(['user', 'lesson'])->get();
        return ProgressResource::collection($progresses);
    }
}
