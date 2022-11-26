<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index(Request $request){
        try {

            $validated = $request->validate([
                'email' => 'required|email:rfc,dns',
                'password' => 'required',
            ]);

            $verifyUser = Admin::where([['email', $request->email], ['password', $request->password]])->first();

            if(!$verifyUser){
                return response()->json([
                    'status' => false,
                    'message' => 'User does not exist',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'status' => true,
                'message' => 'Everything is Good',
                'data' => [
                    'name' => $verifyUser->name
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }


}
