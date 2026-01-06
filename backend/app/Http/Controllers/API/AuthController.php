<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Patient;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class AuthController extends Controller
{
    // register
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:accounts',
            'password' => 'required|min:6|confirmed',
            'full_name' => 'required',
            'phone' => 'nullable|string',
        ]);

        $account = Account::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $role = Role::where('name', 'patient')->first();
        if ($role) {
            $account->roles()->attach($role->id);
        }

        $person = Person::create([
            'account_id' => $account->id,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
        ]);

        Patient::create([
            'person_id' => $person->id,
            'medical_code' => 'MC-' . time()
        ]);

        return response()->json([
            'message' => 'Đăng ký thành công',
            'user' => $account->load('person', 'roles')
        ], 201);
    }

    // login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $account = Account::where('email', $request->email)->first();

        if (!$account || !Hash::check($request->password, $account->password)) {
            return response()->json([
                'message' => 'Thông tin xác thực không hợp lệ',
            ], 401);
        }

        if ($account->status !== 'active') {
            return response()->json([
                'message' => 'Tài khoản không hoạt động',
            ], 403);
        }

        $token = $account->createToken('mediconnect-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'account' => $account,
            'roles' => $account->roles->pluck('name')
        ]);
    }

    // logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Đã đăng xuất'
        ]);
    }
}
