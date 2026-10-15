<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class AuthController extends Controller
{
    public function register(Request $request){
        // dd($request->all());

        user::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'สมัครสมาชิกเรียบร้อยแล้ว',
        ]);
    }
    // Login
    public function login(Request $request)
    {
        // รับข้อมูล email และ password
        $data = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if (auth()->attempt($data)) {
            $user = User::where('email', $request->email)->first();
            $tokenresult = $user->createToken('authToken')->plainTextToken;
            // dd($tokenresult);
        
            return response()->json([
                'status' => true,
                'message' => 'เข้าสู่ระบบสำเร็จ',
                'token' => $tokenresult,
                
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'เข้าสู่ระบบไม่สำเร็จ',
            ]);
        }
    }

    // Logout
    public function logout(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'กรุณาเข้าสู่ระบบก่อน',
        ]);
    }

    $token = $user->currentAccessToken();

    if ($token) {
        $token->delete();
    }

    return response()->json([
        'status' => true,
        'message' => 'ออกจากระบบสำเร็จ',
    ]);
}

    // แสดงข้อมูลผู้ใช้ที่เข้าสู่ระบบ
    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'แสดงข้อมูลผู้ใช้สำเร็จ',
            'data' => $request->user(),
        ]);
    }
}
