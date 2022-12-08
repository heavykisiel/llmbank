<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\customUser;
use Illuminate\Support\Facades\DB;


class customUserController extends Controller
{
    public function index(){
        $customUser = DB::table('users')
        ->select('id')
        ->get();
        return ['Users' => $customUser];

    }
}
