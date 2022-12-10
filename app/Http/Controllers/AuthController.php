<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\encoding;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');
        

        $hashedPassword = DB::table('custom_users')
            ->select('password')
            ->where('name',$login)
            ->get();

        $hashedPassword = $hashedPassword[0]->password;

        if (Hash::check($password, $hashedPassword)) {

            $guid = Str::uuid()->toString();

            $updated = DB::table('custom_users')
                ->where('name',$login)    
                ->update(['token' => $guid]);

            return response()->json([
                'token' => $guid
            ],200);
        }

        return response()->json([
        ],400);
    }
}
