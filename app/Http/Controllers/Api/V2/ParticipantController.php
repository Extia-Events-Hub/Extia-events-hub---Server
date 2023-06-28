<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Event;
use App\Mail\EventTicket;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{
    public function index()
    {
        $participants = Participant::all();
        return response()->json([
            'status' => true,
            'data' => $participants
        ], 200);
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'userName' => 'required',
                'userEmail' => [
                    'required',
                    'email',
                    Rule::unique('participants')->where(function ($query) use ($request) {
                        return $query->where('event_id', $request->input('event_id'));
                    })
                ],
                'check' => 'nullable',
                'event_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $participant = Participant::where('userEmail', $request->input('userEmail'))
                ->where('event_id', $request->input('event_id'))
                ->first();

            if ($participant) {
                return response()->json([
                    'status' => false,
                    'message' => 'Participant with this email already registered for the event.',
                ], 400);
            }

            $event = Event::findOrFail($request->input('event_id'));

            $participantCount = Participant::where('event_id', $event->id)->count();

            if ($participantCount >= $event->max_participants) {
                return response()->json([
                    'status' => false,
                    'message' => 'Maximum participant limit reached for this event.',
                ], 400);
            }

            $participant = new Participant();
            $participant->userName = $request->input('userName');
            $participant->userEmail = $request->input('userEmail');
            $participant->event_id = $request->input('event_id');
            $participant->save();

            $email = new EventTicket($participant->userName, $participant->userEmail, $participant->event_id);
            Mail::send($email);


            return response()->json([
                'status' => true,
                'message' => 'New participant added successfully',
                'participant' => $participant,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add a participant.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function show(Participant $participant)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => $participant
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve a participant.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Participant $participant)
    {
        try {
            $validator = Validator::make($request->all(),[
                'userName' => 'required',
                'userEmail' => 'required',
                'check' => 'nullable',
                'event_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $participant->fill($request->all());


            $participant->save();

            return response()->json([
                'status' => true,
                'message' => 'participant updated successfully',
                'participant' => $participant,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update participant.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Participant $participant)
    {
        try {
            $participant->delete();

            return response()->json([
                'status' => true,
                'message' => 'participant deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete participant.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getParticipantCount($eventId)
    {
        $participantCount = Participant::where('event_id', $eventId)->count();

        return response()->json([
            'status' => true,
            'participantCount' => $participantCount
        ], 200);
    }
}
