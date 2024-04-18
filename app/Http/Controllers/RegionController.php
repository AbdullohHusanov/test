<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $region = Region::all();

        return response()->json([
            'status' => true,
            'message' => 'region list',
            'result' => $region
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
        ]);

        if ($v->fails()) {
            return response()->json(['error' => true, 'message' => $v->messages()], 400);
        }

        $region = Region::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Region successfully created',
            'result' => $region
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $region = Region::find($id);

        if ($region) {
            return response()->json([
                'status' => true,
                'message' => 'Region',
                'result' => $region
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Region not found',
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
        ]);

        if ($v->fails()) {
            return response()->json(['error' => true, 'message' => $v->messages()], 400);
        }

        $region = Region::find($id);

        if ($region) {

            $region->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Region successfully updated',
                'result' => $region
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Region not found',
            'result' => []
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $region = Region::find($id);

        if ($region) {

            $region->delete();

            return response()->json([
                'status' => true,
                'message' => 'Region successfully deleted',
                'result' => $region
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Region not found',
            'result' => []
        ]);
    }

    public function countBranches($region_id)
    {
        $region = Region::with(['districts.branches', 'districts.branches.brand'])->find($region_id);

        $result = [
            'region' => $region->name,
            'districts' => []
        ];

        foreach ($region->districts as $district) {            
            
            $branchesCountByBrand = $district->branches->groupBy('brand_id')->map(function ($branches) {
                return [
                    'brand' => $branches->first()->brand->name,
                    'branch_count' => $branches->count(),
                ];
            });
            
            $brandDatas = [];

            foreach ($branchesCountByBrand as $brandData) {
                $brandDatas[$brandData['brand']] = $brandData['branch_count'];
            }
            
            $result['districts'][] = [
                'name' => $district->name,
                'brand_data' => $brandDatas
            ];
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Regions with brand datas',
            'result' => $result
        ]);
    }
}
