<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $district = District::all();

        return response()->json([
            'status' => true,
            'message' => 'district list',
            'result' => $district
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'region_id' => 'required|integer',
        ]);

        if ($v->fails()) {
            return response()->json(['error' => true, 'message' => $v->messages()], 400);
        }
        
        $district = District::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'District successfully created',
            'result' => $district
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $district = District::find($id);

        if ($district) {
            return response()->json([
                'status' => true,
                'message' => 'district',
                'result' => $district
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'district not found',
            'result' => []
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'region_id' => 'required|integer',
        ]);

        if ($v->fails()) {
            return response()->json(['error' => true, 'message' => $v->messages()], 400);
        }

        $district = District::find($id);

        if ($district) {

            $district::update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'District successfully updated',
                'result' => $district
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'district not found',
            'result' => []
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $district = District::find($id);

        if ($district) {

            $district->delete();

            return response()->json([
                'status' => true,
                'message' => 'District successfully deleted',
                'result' => $district
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'district not found',
            'result' => []
        ]);
    }
}
