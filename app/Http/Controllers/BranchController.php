<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    /** 
     * Branches list.
     *
     * @OA\Get(
     *      path="/api/branches",
     *      operationId="branchList",
     *      summary="Branches list",
     *      tags={"Branches Routes"},
     *      security={{ "bearerAuth": {} }},
     * 
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     */
    public function index()
    {
        $branches = Branch::with('images')->get();

        return response()->json([
            'status' => true,
            'message' => 'Branch list',
            'result' => $branches
        ]);
    }

    /**
     * Branch add.
     *
     * @OA\Post(
     *      path="/api/branches",
     *      operationId="BranchCreate",
     *      summary="Branch add",
     *      tags={"Branches Routes"},  
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Branch name",
     *         required=true,
     *         example="Branch Name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="brand_id",
     *         in="query",
     *         description="Brand Id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="district_id",
     *         in="query",
     *         description="District Id",
     *         required=true,
     *         example="12",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!"),
     *      @OA\Response(response=422, description="Validation error!", @OA\JsonContent()),
     * )
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'brand_id' => 'required|integer',
            'district_id' => 'required|integer',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg'
        ]);

        if ($v->fails()) {
            return response()->json(['error' => true, 'message' => $v->messages()], 422);
        }

        $branch = Branch::create($request->only(['name', 'brand_id', 'district_id']));

        foreach ($request->images as $image) {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
            }

            BranchImage::create([
                'branch_id' => $branch->id,
                'image_path' => '/images/' . $imageName
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Branch successfully created',
            'result' => $branch
        ]);
    }

    /**
     * Branch show.
     *
     * @OA\Get(
     *      path="/api/branches/{id}",
     *      operationId="BranchShow",
     *      summary="Branch show",
     *      tags={"Branches Routes"},  
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Branch id",
     *         required=true,
     *         example="2",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=404, description="Branch not found!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $id
     */

    public function show($id)
    {
        $branch = Branch::find($id);

        if ($branch) {
            return response()->json([
                'status' => true,
                'message' => 'Branch',
                'result' => $branch
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'branch not found',
            'result' => []
        ]);
    }

    /**
     * Branch update.
     *
     * @OA\Put(
     *      path="/api/branches/{id}",
     *      operationId="BranchUpdate",
     *      summary="Branch update",
     *      tags={"Branches Routes"},  
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Branch id",
     *         required=true,
     *         example="143",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Branch name",
     *         required=true,
     *         example="New Branch Name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="brand_id",
     *         in="query",
     *         description="New Brand Id",
     *         required=true,
     *         example="32",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="district_id",
     *         in="query",
     *         description="New District Id",
     *         required=true,
     *         example="3",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=422, description="Validation error!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'brand_id' => 'required|integer',
            'district_id' => 'required|integer',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg'
        ]);

        if ($v->fails()) {
            return response()->json(['error' => true, 'message' => $v->messages()], 400);
        }

        $branch = Branch::find($id);

        if ($branch) {
            $branch::update($request->only(['name', 'brand_id', 'district_id']));

            foreach ($request->images as $image) {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images'), $imageName);
                }
    
                BranchImage::create([
                    'branch_id' => $branch->id,
                    'image_path' => '/images/' . $imageName
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Branch successfully updated',
                'result' => $branch
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'brand not found',
            'result' => []
        ]);

    }

    /**
     * Branch delete info.
     *
     * @OA\Delete(
     *      path="/api/branches/{id}",
     *      operationId="BranchDelete",
     *      summary="Branch delete",
     *      tags={"Branches Routes"},  
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Branch id",
     *         required=true,
     *         example="143",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *     @OA\Response(response=404, description="Branch not found!", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Delete error!", @OA\JsonContent()),
     *     @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $id
     */
    public function destroy($id)
    {
        $branch = Branch::find($id);

        if ($branch) {

            $branch->delete();

            return response()->json([
                'status' => true,
                'message' => 'Branch successfully deleted',
                'result' => $branch
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'branch not found',
            'result' => []
        ]);
    }
}
