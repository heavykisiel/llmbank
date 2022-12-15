<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use App\Models\customUser;
use App\Models\BankAccount;
use App\Models\transaction;


class BankController extends Controller
{
    //

    public function bankNumbers (Request $request)
    {
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
        $validator = Validator::make($request->all(), [
            'from' => 'required|exists:bank_account,accNumber',
            'to' => 'required|exists:bank_account,accNumber',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'string|max:255',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        $from = $request->input('from');
        $to = $request->input('to');
        $amount = $request->input('amount');
        $token = $request->input('token');
        $description = $request->input('description');

        //sprawdzenie czy konto należy do odpowieniego użytkownika
        $user = customUser::where('token', $token)->first();

        if(!$user) { return response()->json([
            'success' => false,
            'errors' => 'Niepoprawny token',
        ], 400); }
        
        $bankAccount = BankAccount::where('user_id', $user->id)
            ->where('accNUmber',$from)
            ->first();
        
        $toBank = BankAccount::where('accNUmber',$to)
            ->first();

        if($bankAccount) {
            if ($toBank == false) { 
                return response()->json([
                'errMessage' => 'Konto na które próbujesz zrobić przelew nie istnieje w naszym banku.',],400); 
            }
            
            
            $transaction = transaction::create([
                'amount' => $amount,
                'description' => $description,
                'from_bank_id' => $from,
                'to_bank_id' => $to,
                'currency' => $bankAccount->currency
            ]);

            $bankAccount->decrement('balance',$amount);
            $toBank->increment('balance',$amount);

            return response()->json(['succes' => true, 'newBalance' => $bankAccount->balance ],200);
        } else
        {
            return response()->json([
                'errMessage' => 'Próbujesz zrobić przelew z nie swojego konta.',
            ],400);
        }        
    }

    public function showTransactionHistory(Request $request){
        $token = $request->input('token');
        $accNumber = $request->input('accNumber');

        $exist = DB::table('custom_users')
            ->join('bank_account','custom_users.id', '=', 'bank_account.user_id')
            ->where('custom_users.token',$token)
            ->where('bank_account.accNumber',$accNumber)
            ->select('bank_account.accNumber')
            ->first();

        if ($exist){
            $transactions = transaction::where('to_bank_id',$exist->accNumber)->orWhere('from_bank_id',$exist->accNumber)->get();

            return response()->json($transactions, 200);

        }else{
            return response()->json([
                'success' => false,
                'errors' => 'Brak dostępu',
            ], 400);
        }
    }


    public function selectedHistory(Request $request)
    {
        $token = $request->input('token');
        $accNumber = $request->input('accNumber');

        $exist = DB::table('custom_users')
            ->where('token',$token)
            ->where('permissions','>=',1)
            ->first();

        if(!$exist){
            return response()->json([
                'success' => false,
                'errors' => 'Brak uprawnień',
            ], 400);
        }

        $transactions = transaction::where('to_bank_id',$accNumber)->orWhere('from_bank_id',$accNumber)->get();
        return response()->json($transactions, 200);

    }
    


}
