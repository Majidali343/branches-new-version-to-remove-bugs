<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\Group;
use App\Models\Task;
use App\Models\GroupHousehold;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
   
    public function getEvents()
    {
        try {
            $user = auth()->user();
            $isAdmin = GroupUser::where(['user_id' => $user->id, 'is_admin' => 1, 'status' => 1])->first();

            if (!$isAdmin) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to view events for this group.',
                ], 403);
            }

            $events = Event::where('group_id', $isAdmin->group_id)->get();
            if ($events->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No event found for this group.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Events fetched successfully.',
                'data' => $events,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch events.',
            ], 500);
        }
    }

    public function addEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'location' => 'required',
            'start_date' => 'required|date',
            'group_id' => 'required|exists:groups,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors(),
                'message' => 'Validation failed.',
            ], 422);
        }

        $user = auth()->user();
        $isAdmin = GroupUser::where(['user_id' => $user->id, 'group_id' => $request->group_id, 'is_admin' => 1, 'status' => 1])->first();

        if (!$isAdmin) {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to add an event to the group.',
            ], 403);
        }
        
        $data = $request->all();
        $randomString = Str::random(10);
        $timestampString = Carbon::now()->timestamp;
        $data['serial_id'] =$randomString . $timestampString;
        
        $event = Event::create($data);
        EventAttendee::where(['event_id' => $event->id, 'user_id' => $user->id]);
        $event['user'] = [$user];

        return response()->json([
            'status' => true,
            'message' => 'Event added successfully.',
            'data' => $event,
        ], 201);
    }

    public function getEventsByGroup(Request $request)
    {
        try {
            $user = auth()->user();
    
            $validator = Validator::make($request->all(), [
                'group_id' => 'required|exists:groups,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Validation failed.',
                ], 422);
            }
            $isAdmin = GroupUser::where(['user_id' => $user->id, 'group_id' => $request->group_id, 'is_admin' => 1, 'status' => 1])->first();
    
            if (!$isAdmin) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to view events for this group.',
                ], 403);
            }
    
            $group = Group::find($isAdmin->group_id);
            if($group){
                $group->profile_img = $group->profile_img ? asset('images/groups') . '/' . $group->profile_img : null;
            }
            $events = Event::where('group_id', $isAdmin->group_id)->get();
            if ($events->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No events found for this group.',
                ], 404);
            }
    
            $responseData = [
                'group' => $group,
                'events' => $events,
            ];
    
            return response()->json([
                'status' => true,
                'message' => 'Events fetched successfully.',
                'data' => $responseData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch events.',
            ], 500);
        }
    }
    

    public function updateEvent(Request $request)
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'name' =>'string',
            'description' =>'string',
            'start_date' =>'date',
            'location' => 'string',
            'event_id' => 'required|exists:events,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors(),
                'message' => 'Validation failed.',
            ], 422);
        }

        $event = Event::find($request->event_id);
        
        $isAdmin = GroupUser::where(['user_id' => $user->id, 'group_id' => $event->group_id, 'is_admin' => 1, 'status' => 1])->first();
    
        if (!$isAdmin) {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to update the event.',
            ], 403);
        }
    
        $event->update($request->all());
    
        return response()->json([
            'status' => true,
            'message' => 'Event updated successfully.',
            'data'    => $event
        ]);
    }
    
    public function deleteEvent(Request $request)
    {
        $user = auth()->user();
        $isAdmin = GroupUser::where(['user_id' => $user->id, 'is_admin' => 1, 'status' => 1])->first();
    
        if (!$isAdmin) {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to delete the event.',
            ], 403);
        }
    
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors(),
                'message' => 'Validation failed.',
            ], 422);
        }
    
        $eventId = $request->input('event_id');
        $event = Event::find($eventId);
    
        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found.',
            ], 404);
        }
    
        $event->delete();
    
        return response()->json([
            'status' => true,
            'message' => 'Event deleted successfully.',
        ]);
    }

    public function show($id) {
        $user = auth()->user();

        $event = Event::find($id);
    
        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found.',
            ], 404);
        }

        $eventAttendees = EventAttendee::where('event_id', $id)->pluck('user_id')->toArray();

        $eventAttendeesDetails = User::find($eventAttendees);
        if ($eventAttendeesDetails) {
            $eventAttendeesDetails->each(function ($user) {
                $user->profile_img = $user->profile_img ? asset('images/profile') . '/' . $user->profile_img : null;
            });
        }
        $statusMapping = [
            0 => 'Pending',
            1 => 'In Progress',
            2 => 'Completed',
            5 => 'Unassigned',
        ];
        $isOrganizer = GroupUser::where(['user_id' => $user->id, 'group_id' => $event->group_id, 'is_admin' => 1, 'status' => 1])->exists();

        $isAttending = in_array($user->id, $eventAttendees);

        if($isOrganizer){

            $assignedTasks =  Task::where(['event_id'=> $id,  ])->get();
        }else{

            $assignedTasks =  Task::where(['event_id'=> $id, 'assigned_to'=> $user->id])->get();

        }
       
        
        
        $unassignedTasks =  Task::where(['event_id'=> $id, 'status'=> '5'])->get();

        $totalEventCost = $assignedTasks->isEmpty() ? 0 : $assignedTasks->sum('cost') + $unassignedTasks->sum('cost');

        return response()->json([
            'status' => true,
            'message' => 'Event Details',
            "data" => [
                'event' => $event,
                'attendees' => $eventAttendeesDetails,
                'is_organizer' => $isOrganizer,
                'is_attending' => $isAttending,
                'assigned_tasks' => statusMaping($assignedTasks, $statusMapping),
                'unassigned_tasks' => statusMaping($unassignedTasks, $statusMapping),
                'total_event_cost' => $totalEventCost,

            ]
        ]);
    }

    public function addEventAttendee(Request $request){
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors(),
                'message' => 'Validation failed.',
            ], 422);
        }
        
        $event = Event::find($request->event_id);

        // $isAdmin = GroupUser::where(['user_id' => $user->id, 'group_id' => $event->group_id, 'status' => 1])->first();
    
        // if (!$isAdmin) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'You do not have permission to attend the event.',
        //     ], 403);
        // }

        // Check if the user is already attending the event
        $existingAttendee = EventAttendee::where(['event_id' => $request->event_id, 'user_id' => $user->id])->exists();
        if ($existingAttendee) {
            return response()->json([
                'status' => false,
                'message' => 'You are already attending this event.',
            ], 422);
        }
        
        $request['user_id'] = $user->id;

        $isGroupMember = GroupUser::where(['group_id' => $event->group_id, 'user_id' => $user->id, 'status' => 1])->exists();
        $AttendeeHouseholdId = User::find($user->id)->userHousehold?->household_id;
        $isGroupHousehold = GroupHousehold::where(['group_id' => $event->group_id, 'household_id' => $AttendeeHouseholdId, 'status' => 1])->exists();

        if($isGroupMember || $isGroupHousehold){
            $eventAttendee = EventAttendee::create($request->all());

            if($eventAttendee){
                return response()->json([
                    'status' => true,
                    'message' => 'Event Attendee added successfully.'
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to add event attendee.',
                ], 500);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Failed to add event attendee. Given User is not member of group.',
            ], 500);
        }
    }

    public function getAllAttendingEvents(){
        $user = auth()->user();
        $eventIds = EventAttendee::where('user_id', $user->id)->pluck('event_id')->toArray();
        // $eventIds = [1,2,3];
        $events = Event::find($eventIds);

        $events->map(function($event) {
            $event->task = Task::where(['event_id' => $event->id, 'assigned_to' => auth()->user()->id])->get();
        });

        return response()->json([
            'status' => true,
            'message' => 'Events of User',
            'data' => $events
        ]);
    }

}
