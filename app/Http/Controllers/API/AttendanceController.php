<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function checkin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Check if already checked in today for this classroom
        $existing = Attendance::where('user_id', $user->id)
            ->where('classroom_id', $request->classroom_id)
            ->whereDate('checkin_at', today())
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Already checked in today for this classroom'
            ], 409);
        }

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'classroom_id' => $request->classroom_id,
            'checkin_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in successful',
            'data' => new AttendanceResource($attendance->load(['user', 'classroom']))
        ]);
    }

    public function getByClassroomAndUser($classroomId, $userId)
    {
        $attendances = Attendance::where('classroom_id', $classroomId)
            ->where('user_id', $userId)
            ->with(['user', 'classroom'])
            ->get();

        return AttendanceResource::collection($attendances);
    }
}
