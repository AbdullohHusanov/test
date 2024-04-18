<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /** 
     * Users list.
     *
     * @OA\Get(
     *      path="/api/users",
     *      operationId="usersList",
     *      summary="Users list",
     *      tags={"Users Routes"},
     *      security={{ "bearerAuth": {} }},
     * 
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'status' => true,
            'message' => 'Users list',
            'result' => $users
        ]);
    }

    /**
     * User add.
     *
     * @OA\Post(
     *      path="/api/users",
     *      operationId="UserCreate",
     *      summary="User add",
     *      tags={"Users Routes"},  
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User name",
     *         required=true,
     *         example="User Name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Email",
     *         required=true,
     *         example="example@test.com",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="Phone number",
     *         required=true,
     *         example="990001122",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         example="user12345",
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!"),
     *      @OA\Response(response=422, description="Validation error!", @OA\JsonContent()),
     * )
     *
     * @param Request $request
     */
    public function create(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'      => 'required|string|min:3|max:32',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'required|numeric|digits:9|unique:users,phone',
            'password'  => 'required|string|min:6|max:64',
        ]);

        if ($v->fails()) {
            return response()->json(['error' => true, 'message' => $v->messages()], 400);
        }

        $request->password = Hash::make($request->password);
        $user = User::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'User successfully created',
            'result' => $user
        ]);
    }

    /**
     * User show.
     *
     * @OA\Get(
     *      path="/api/users/{id}",
     *      operationId="UserShow",
     *      summary="User show",
     *      tags={"Users Routes"},  
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         example="2",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=404, description="User not found!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $id
     */

    public function show($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'status' => true,
                'message' => 'User',
                'result' => $user
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'User not found',
            'result' => []
        ]);
    }
    
    /**
     * User update.
     *
     * @OA\Put(
     *      path="/api/users/{id}",
     *      operationId="UserUpdate",
     *      summary="User update",
     *      tags={"Users Routes"},  
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         example="143",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User name",
     *         required=true,
     *         example="New User Name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Email",
     *         required=true,
     *         example="example@test.com",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="Phone number",
     *         required=true,
     *         example="990001122",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         example="user12345",
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=422, description="Validation error!", @OA\JsonContent()),
     *      @OA\Response(response=404, description="User not found!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'name'      => 'required|string|min:3|max:32',
            'email'     => 'required|email|unique:users,email,'. $id,
            'phone'     => 'required|numeric|digits:9|unique:users,phone,'. $id,
            'password'  => 'required|string|min:6|max:64',
        ]);

        if ($v->fails()) {
            return response()->json(['error' => true, 'message' => $v->messages()], 400);
        }

        $user = User::find($id);

        if ($user) {
        
            $user::update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'User successfully updated',
                'result' => $user
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'User not found',
            'result' => []
        ]);
    }
    
    /**
     * User delete info.
     *
     * @OA\Delete(
     *      path="/api/users/{id}",
     *      operationId="UserDelete",
     *      summary="User delete",
     *      tags={"Users Routes"},  
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         example="143",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *     @OA\Response(response=404, description="User not found!", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Delete error!", @OA\JsonContent()),
     *     @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $id
     */

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
        
            $user::delete();

            return response()->json([
                'status' => true,
                'message' => 'User successfully deleted',
                'result' => []
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'User not found',
            'result' => []
        ]);
    }
}
