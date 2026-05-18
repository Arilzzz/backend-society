<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Society;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $society = Society::where('id_card_number', $request->id_card_number)
            ->where('password', $request->password)
            ->first();

        if (!$society) {
            return response()->json([
                'message' => 'ID Card Number or Password incorrect'
            ], 401);
        }

        $token = md5($society->id_card_number);

        $society->login_tokens = $token;
        $society->save();

        return response()->json([
            'name' => $society->name,
            'born_date' => $society->born_date,
            'gender' => $society->gender,
            'address' => $society->address,
            'token' => $token,
            'regional' => [
                'id' => $society->regional->id,
                'province' => $society->regional->province,
                'district' => $society->regional->district
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $token = $request->token;

        $society = Society::where('login_tokens', $token)->first();

        if (!$society) {
            return response()->json([
                'message' => 'Invalid token'
            ], 401);
        }

        $society->login_tokens = null;
        $society->save();

        return response()->json([
            'message' => 'Logout success'
        ], 200);
    }
}
