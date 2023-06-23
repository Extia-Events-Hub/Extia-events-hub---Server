<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10); 
        return response()->json([
            'status' => true,
            'data' => $products
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'category' => 'required',
                'description' => 'required',
                'image' => 'required|image|mimes:png,jpg,jpeg',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $product = new Product($request->all());
            $product->user_id = $request->user()->id;

            if ($request->hasFile('image')) {
                $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
                $product->image = $uploadedFileUrl;
            }

            $product->save();

            return response()->json([
                'status' => true,
                'message' => 'New product Added successfully',
                'product' => $product,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Product $product)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'category' => 'required',
                'description' => 'required',
                'image' => 'nullable|image',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $product->fill($request->except('image'));

            if ($request->hasFile('image')) {
                Cloudinary::destroy($product->image);
                $uploadedFile = $request->file('image');                
                $uploadResult = Cloudinary::upload($uploadedFile->getRealPath());
                $product->image = $uploadResult->getSecurePath();
            }

            $product->save();

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully',
                'product' => $product,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        try {            
            Cloudinary::destroy($product->image);            
            $product->delete();

            return response()->json([
                'status' => true,
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function assetsByUser()
    {
        try {
            $products = Product::select(DB::raw('count(products.id) as count, users.name'))
                ->join('users', 'users.id', '=', 'products.user_id')
                ->groupBy('users.name')
                ->get();

            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve assets by user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function all()
    {
        try {
            $products = Product::select('products.*', 'users.name as user')
                ->join('users', 'users.id', '=', 'products.user_id')
                ->get();

            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve assets.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
