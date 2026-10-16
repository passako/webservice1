<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkAttendance;

class WorkAttendanceController extends Controller
{
    // แสดงข้อมูลทั้งหมด
    public function index()
    {
        $attendances = WorkAttendance::all();

        return response()->json($attendances);
    }

    /**
     * แสดงข้อมูลตาม ID
     */
    public function show($id)
    {
        $attendance = WorkAttendance::find($id);

        if (!$attendance) {
            return response()->json([
                'status' => false,
                'message' => 'ไม่พบข้อมูลการเข้างาน'
            ]);
        }

        return response()->json($attendance);
    }

    /**
     * บันทึกข้อมูลเช็คอิน
     */
    public function store(Request $request5)
    {
        $data = $request->validate([
            'employee_name' => 'required|string|max:255',
            'work_date' => 'required|date',
            'check_in' => 'required|date_format:H:i:s',
            'note' => 'nullable|string',
        ]);

        $data['check_out'] = null;
        $data['status'] = 'กำลังทำงาน';

        $attendance = WorkAttendance::create($data);

        return response()->json([
            'status' => true,
            'message' => 'บันทึกเวลาเช็คอินเรียบร้อยแล้ว',
            'data' => $attendance
        ], 201);
    }

    /**
     * แก้ไขข้อมูลการเข้างาน
     */
    public function update(Request $request, $id)
    {
        $attendance = WorkAttendance::find($id);

        if (!$attendance) {
            return response()->json([
                'status' => false,
                'message' => 'ไม่พบข้อมูลการเข้างาน'
            ], 404);
        }

        $data = $request->validate([
            'employee_name' => 'sometimes|required|string|max:255',
            'work_date' => 'sometimes|required|date',
            'check_in' => 'sometimes|required|date_format:H:i:s',
            'check_out' => 'sometimes|nullable|date_format:H:i:s',
            'status' => 'sometimes|required|string|max:255',
            'note' => 'sometimes|nullable|string',
        ]);

        $attendance->update($data);

        return response()->json([
            'status' => true,
            'message' => 'แก้ไขข้อมูลการเข้างานเรียบร้อยแล้ว',
            'data' => $attendance
        ]);
    }

    /**
     * ลบข้อมูลการเข้างาน
     */
    public function destroy($id)
    {
        $attendance = WorkAttendance::find($id);

        if (!$attendance) {
            return response()->json([
                'status' => false,
                'message' => 'ไม่พบข้อมูลการเข้างาน'
            ], 404);
        }

        $attendance->delete();

        return response()->json([
            'status' => true,
            'message' => 'ลบข้อมูลการเข้างานเรียบร้อยแล้ว'
        ]);
    }

    /**
     * บันทึกเวลาเช็คเอาต์
     */
    public function checkout($id)
    {
        $attendance = WorkAttendance::find($id);

        if (!$attendance) {
            return response()->json([
                'status' => false,
                'message' => 'ไม่พบข้อมูลการเข้างาน'
            ], 404);
        }

        if ($attendance->check_in === null) {
            return response()->json([
                'status' => false,
                'message' => 'กรุณาเช็คอินก่อนเช็คเอาต์'
            ], 422);
        }

        if ($attendance->check_out !== null) {
            return response()->json([
                'status' => false,
                'message' => 'รายการนี้เช็คเอาต์แล้ว'
            ], 422);
        }

        $attendance->update([
            'check_out' => now()->format('H:i:s'),
            'status' => 'เลิกงานแล้ว'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'บันทึกเวลาเช็คเอาต์เรียบร้อยแล้ว',
            'data' => $attendance
        ]);
    }
}
