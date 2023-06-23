<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CityController extends Controller
{
    public function index()
    {
        $Cities = City::all(); 
        return response()->json([
            'status' => true,
            'data' => $Cities
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',                
                'logo' => 'required|image|mimes:png,jpg,jpeg',                
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $City = new City($request->all());
            $City->user_id = $request->user()->id;

            if ($request->hasFile('logo')) {
                $uploadedFileUrl = Cloudinary::upload($request->file('logo')->getRealPath())->getSecurePath();
                $City->logo = $uploadedFileUrl;
            }

            $City->save();

            return response()->json([
                'status' => true,
                'message' => 'New City created successfully',
                'City' => $City,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create a City.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(City $City)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => $City
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve a City.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, City $City)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',                
                'logo' => 'required|image|mimes:png,jpg,jpeg',                
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $City->fill($request->except('image'));

            if ($request->hasFile('logo')) {
                Cloudinary::destroy($City->logo);
                $uploadedFile = $request->file('logo');                
                $uploadResult = Cloudinary::upload($uploadedFile->getRealPath());
                $City->image = $uploadResult->getSecurePath();
            }

            $City->save();

            return response()->json([
                'status' => true,
                'message' => 'City updated successfully',
                'City' => $City,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update City.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(City $City)
    {
        try {            
            Cloudinary::destroy($City->logo);            
            $City->delete();

            return response()->json([
                'status' => true,
                'message' => 'City deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete City.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
