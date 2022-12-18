<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\encoding;
use App\Models\customUser;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        $User = DB::table('custom_users')
            ->where('name',$login)
            ->first();

        if (!$User) {
            return response()->json([
                'success' => false,
                'errors' => 'Niepoprawnde dane',
            ], 400);
        }

        $hashedPassword = $User->password;

        if (Hash::check($password, $hashedPassword)) {

            $guid = Str::uuid()->toString();

            $updated = DB::table('custom_users')
                ->where('name',$login)    
                ->update(['token' => $guid]);

            return response()->json([
                'User' => 
                [ 
                    'first_name' => $User->first_name,
                    'last_name' => $User->last_name,
                    'userName' => $User->name,
                    'permissions' => $User->permissions
                 ],
                'token' => $guid
            ],200);
        }

        return response()->json([
        ],400);
    }

    public function register(Request $request)
    {

        // Pobierz dane użytkownika z zapytania HTTP
        $data = $request->validate([
            'username' => 'required|string|max:255',
            //'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $userWithSameName = customUser::where('name', $data['username'])->first();
        if ($userWithSameName) {
            return response()->json(['errorMessage'=>'Użytkownik o takiej nazwie już istnieje!'],400);
        }

        $user = customUser::create([
            'name' => $data['username'],
            'password' => bcrypt($data['password']),
        ]);
        
        return response()->json($user);
    }


    public function userList(Request $request)
    {
        $token = $request->input('token');

        $exist = DB::table('custom_users')
            ->where('token',$token)
            ->where('permissions','>=',2)
            ->first();


        if(!$exist){
            return response()->json([
                'success' => false,
                'errors' => 'Brak uprawnień',
            ], 400);
        }

        $users = customUser::get();

        return response()
            ->json($users, 200); 


    }

    public function change(Request $request)
    {
        // Pobierz dane wejściowe od użytkownika
        $input = $request->all();

        $rules = [
            'user_id' => 'required | exists:custom_users,id',
            'new_first_name' => 'string|max:30',
            'new_last_name' => 'string|max:30',
            'new_password' => 'string|min:8|confirmed',
            'new_password_confirmation' => 'required_with:new_password | same:new_password',
            'token' => 'required'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            // Jeśli dane wejściowe są niepoprawne, zwróć błąd
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }


        // Znajdź użytkownika na podstawie jego identyfikatora
        $admin = customUser::where('token',$input['token'])->where('permissions','>=',2)->first();
        if(!$admin){
            return response()->json(['success' => false,'errors' => 'Niepoprawny token'],400);
        }

        $user = customUser::where('id',$input['user_id'])->first();
        if(!$user){
            return response()->json(['success' => false,'errors' => 'Nieznaleziono użytkownika'],400);
        }
        
        if ($request->filled('new_last_name')){
            $user->last_name = $input['new_last_name'];
        }

        if ($request->filled('new_password')){
            $user->password = Hash::make($input['new_password']);
        }

        if($request->filled('new_first_name')){
                $user->first_name = $input['new_first_name'];
            }
        $user->save();

            // Zwróć odpowiedź z sukcesem
        return response()->json(['status' => 'success']);

    }


}
