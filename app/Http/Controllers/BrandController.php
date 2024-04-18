<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /** 
     * Brands list.
     *
     * @OA\Get(
     *      path="/api/brands",
     *      operationId="brandList",
     *      summary="Brands list",
     *      tags={"Brands Routes"},
     *      security={{ "bearerAuth": {} }},
     * 
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     */
    public function index()
    {
        $brands = Brand::all();

        return response()->json([
            'status' => true,
            'message' => 'Brand list',
            'result' => $brands
        ]);
    }

    /**
     * Brand add.
     *
     * @OA\Post(
     *      path="/api/brands",
     *      operationId="BrandCreate",
     *      summary="Brand add",
     *      tags={"Brands Routes"},  
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Brand name",
     *         required=true,
     *         example="Brand Name",
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        if ($v->fails()) {
            return response()->json(['error' => true, 'message' => $v->messages()], 400);
        }
        
        $brands = new Brand();
        $brands->name = $request->name;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $brands->image = '/images/' . $imageName;
        }
        
        $brands->save();

        return response()->json([
            'status' => true,
            'message' => 'Brand successfully created',
            'result' => $brands
        ]);
    }

    /**
     * Brand show.
     *
     * @OA\Get(
     *      path="/api/brands/{id}",
     *      operationId="BrandShow",
     *      summary="Brand show",
     *      tags={"Brands Routes"},  
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Brand id",
     *         required=true,
     *         example="2",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=404, description="Brand not found!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $id
     */
    public function show($id)
    {
        $brand = Brand::find($id);

        if ($brand) {
            return response()->json([
                'status' => true,
                'message' => 'Brand',
                'result' => $brand
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'brand not found',
            'result' => []
        ]);
    }

    /**
     * Brand update.
     *
     * @OA\Put(
     *      path="/api/brands/{id}",
     *      operationId="BrandUpdate",
     *      summary="Brand update",
     *      tags={"Brands Routes"},  
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Brand id",
     *         required=true,
     *         example="143",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Brand name",
     *         required=true,
     *         example="New Brand Name",
     *         @OA\Schema(type="string")
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
            'name'      => 'required|string|min:3|max:32',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        if ($v->fails()) {
            return response()->json(['error' => true, 'message' => $v->messages()], 400);
        }

        $brand = Brand::find($id);

        if ($brand) {

            $brand->name = $request->name;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $brand->image = '/images/' . $imageName;
            }

            $brand->update();

            return response()->json([
                'status' => true,
                'message' => 'Brand successfully updated',
                'result' => $brand
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'brand not found',
            'result' => []
        ]);
    }


    /**
     * Brand delete info.
     *
     * @OA\Delete(
     *      path="/api/brands/{id}",
     *      operationId="BrandDelete",
     *      summary="Brand delete",
     *      tags={"Brands Routes"},  
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Brand id",
     *         required=true,
     *         example="143",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *     @OA\Response(response=404, description="Brand not found!", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Delete error!", @OA\JsonContent()),
     *     @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $id
     */
    public function destroy($id)
    {
        $brand = Brand::find($id);

        if ($brand) {

            $brand->delete();

            return response()->json([
                'status' => true,
                'message' => 'Brand successfully deleted',
                'result' => $brand
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'brand not found',
            'result' => []
        ]);
    }
}
