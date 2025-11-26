<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Models\Classroom;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lessons = Lesson::with('classroom')->get();
        return LessonResource::collection($lessons);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required|exists:classrooms,id',
            'title' => 'required|string|max:255',
            'file_url' => 'nullable|url',
            'type' => 'required|in:pdf,video',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $lesson = Lesson::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Lesson created successfully',
            'data' => new LessonResource($lesson->load('classroom'))
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        return new LessonResource($lesson->load('classroom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'sometimes|required|exists:classrooms,id',
            'title' => 'sometimes|required|string|max:255',
            'file_url' => 'nullable|url',
            'type' => 'sometimes|required|in:pdf,video',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $lesson->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Lesson updated successfully',
            'data' => new LessonResource($lesson->load('classroom'))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Lesson deleted successfully'
        ]);
    }

    /**
     * Get lessons by classroom.
     */
    public function getLessonsByClassroom(Classroom $classroom)
    {
        $lessons = $classroom->lessons()->with('classroom')->get();
        return LessonResource::collection($lessons);
    }
}
