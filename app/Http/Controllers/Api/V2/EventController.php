<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all(); 
        return response()->json([
            'status' => true,
            'data' => $events
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'shortDescription' => 'required',                
                'longDescription' => 'required',
                'mode' => 'required',
                'startDate' => 'required',
                'endDate'=> 'nullable',
                'startTime' => 'required',
                'endTime' => 'nullable',                
                'image' => 'required|image|mimes:png,jpg,jpeg,webp',  
                'user_id' => 'required',              
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $event = new Event($request->all());
            $event->user_id = $request->user()->id;

            if ($request->hasFile('image')) {
                $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
                $event->image = $uploadedFileUrl;
            }

            $event->save();

            return response()->json([
                'status' => true,
                'message' => 'New event created successfully',
                'event' => $event,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create a event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Event $event)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => $event
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve a event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Event $event)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'shortDescription' => 'required',                
                'longDescription' => 'required',
                'mode' => 'required',
                'startDate' => 'required',
                'endDate'=> 'nullable',
                'startTime' => 'required',
                'endTime' => 'nullable',                
                'image' => 'required|image|mimes:png,jpg,jpeg',  
                'user_id' => 'required',              
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $event->fill($request->except('image'));

            if ($request->hasFile('image')) {
                Cloudinary::destroy($event->image);
                $uploadedFile = $request->file('image');                
                $uploadResult = Cloudinary::upload($uploadedFile->getRealPath());
                $event->image = $uploadResult->getSecurePath();
            }

            $event->save();

            return response()->json([
                'status' => true,
                'message' => 'event updated successfully',
                'event' => $event,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Event $event)
    {
        try {            
            Cloudinary::destroy($event->image);            
            $event->delete();

            return response()->json([
                'status' => true,
                'message' => 'event deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
