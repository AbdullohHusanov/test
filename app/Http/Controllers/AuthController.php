<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
     /**
     * User login api.
     *
     * @OA\Post(
     *      path="/api/login",
     *      operationId="authLogin",
     *      summary="User login",
     *      tags={"Authentication"},
     *      @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="User's phone number",
     *         required=true,
     *         example="+998912223344",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         example="user12345",
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Response(response=200, description="Success!"),
     *      @OA\Response(response=422, description="Validation error!"),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param Request $request
     */
    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:9',
            'password' => 'required|string'
        ]);

        if ($v->fails()) {
            return response()->json(['error' => true, 'message' => $v->messages()], 422);
        }
        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('ClientAccessToken', ['*'], Carbon::now()->addMinutes(60 * 24))->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'successfully logged in',
                'token' => $token,
                'type' => 'Bearer'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Wrong phone or password',
            'result' => []
        ], 401);
    }


    /**
     * User logout.
     *
     * @OA\Post(
     *      path="/api/logout",
     *      operationId="userLogout",
     *      summary="User logout",
     *      tags={"Authentication"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Response(response=200, description="Success!"),
     * )
     *
     * @param Request $request
     */

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
    }

    
}
