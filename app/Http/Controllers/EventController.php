<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        $event = Event::latest()->get();

        return response()->json([
            'success'=>true,
            'message'=>"List Data Event",
            "data"=>$event
        ], 200);
    }

    public function show($id)
    {
        $event = Event::findOrfail($id);

        return response()->json([
            'success'=>true,
            'message'=>"Single Data Event",
            "data"=>$event
        ]);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'title_event'=> 'required',
            'description'=>'required',
            'date'=>'nullable|date',
            'quota'=>'required|integer',
            'type_event'=>'required',
            'event_price'=>'required|integer'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(),400);
        }

        $event = Event::create([
            'title_event'=>$request->title_event,
            'description'=>$request->description,
            'date'=>$request->date,
            'quota'=>$request->quota,
            'type_event'=>$request->type_event,
            'event_price'=>$request->event_price,
        ]);
        if ($event) {
            return response()->json([
                'success'=>true,
                'message'=>'Event Created',
                'data'=>$event
            ],201);
        }

        return response()->json([
            'success'=>false,
            'message'=>'Event failed to save'
        ],409);
    }

    public function update(Request $request, Event $event)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'title_event'=> 'required',
            'description'=>'required',
            'date'=>'nullable|date',
            'quota'=>'required|integer',
            'type_event'=>'required',
            'event_price'=>'required|integer'
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find event by ID
        $event = Event::findOrFail($event->id);

        if($event) {

            //update event
            $event->update([
                'title_event'=>$request->title_event,
                'description'=>$request->description,
                'date'=>$request->date,
                'quota'=>$request->quota,
                'type_event'=>$request->type_event,
                'event_price'=>$request->event_price,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Event Updated',
                'data'    => $event
            ], 200);

        }

        //data event not found
        return response()->json([
            'success' => false,
            'message' => 'Event Not Found',
        ], 404);

    }
    public function destroy($id)
    {
        //find event by ID
        $event = Event::findOrfail($id);

        if($event) {

            //delete event
            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event Deleted',
            ], 200);

        }

        //data event not found
        return response()->json([
            'success' => false,
            'message' => 'Event Not Found',
        ], 404);
    }
}
