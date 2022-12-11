<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\customUser;
use App\Models\BankAccount;

class BankController extends Controller
{
    //

    public function bankNumbers (Request $request){
        $token = $request['token'];
        
        $user = customUser::where('token', $token)->first();

        if ($user) {
            $bankAccounts = BankAccount::where('user_id', $user->id)->get();

            return response()->json($bankAccounts,200);
        } else
        {
            return response()->json(['errorMessage'=>'nieprawidłowy token'],400);
        }
    }

    public function transfer(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $amount = $request->input('amount');

        // tutaj należy dodać kod do przeprowadzenia przelewu
        // pomiędzy kontami $from i $to o kwocie $amount

        return response()->json([
            'success' => true,
        ]);
    }
}
