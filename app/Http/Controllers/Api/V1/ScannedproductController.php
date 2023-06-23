<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Scannedproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScannedproductController extends Controller
{
    public function index()
    {
        $scannedproducts = Scannedproduct::paginate(10); 
        return response()->json([
            'status' => true,
            'data' => $scannedproducts
        ], 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scanDate' => 'required',
            'sore' => 'required',            
            'user_id' => 'required',
            'product_id' => 'nullable',
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $scannedproduct = new Scannedproduct($request->input());
        $scannedproduct->user_id = auth()->id();
        $scannedproduct->save();

        return response()->json([
            'status' => true,
            'message' => 'New scannedproduct added Successfully'
        ], 200);
    }


    public function show(Scannedproduct $scannedproduct)
    {
        return response()->json(['status' => true, 'data' => $scannedproduct]);
    }


    public function update(Request $request, Scannedproduct $scannedproduct)
    {
        $validator = Validator::make($request->all(), [
            'scanDate' => 'required',
            'sore' => 'required',            
            'user_id' => 'required',
            'product_id' => 'nullable',
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $scannedproduct->user_id = auth()->id();
        $scannedproduct->update($request->input());

        return response()->json([
            'status' => true,
            'message' => 'Scannedproduct Updated successfully'
        ], 200);
    }


    public function destroy(Scannedproduct $scannedproduct)
    {
        $scannedproduct->delete();
        return response()->json([
            'status' => true,
            'message' => 'Scannedproduct Deleted successfully'
        ], 200);
    }

    public function scannedproductsByUser()
    {
        $scannedproducts = Scannedproduct::select(DB::raw('count(scannedproducts.id) as count', 'users.name'))->join('users', 'users.id', '=', 'scannedproducts.user_id')->groupBy('users.name')->get();
        return response()->json($scannedproducts);
    }

    public function all()
    {
        $scannedproducts = Scannedproduct::select('scannedproducts.*', 'users.name as user')->join('users', 'users.id', '=', 'scannedproducts.user_id')->get();
        return response()->json($scannedproducts);
    }
}
