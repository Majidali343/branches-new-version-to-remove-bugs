<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Interfaces\NotificationRepositoryInterface;
use App\Models\GroupUser;
use App\Models\Task;
use App\Models\User;
use App\Models\EventAttendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class TaskController extends Controller
{

    private NotificationRepositoryInterface $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function add(Request $request)
    {
        try {
            $user = auth()->user();
            $isAdmin = GroupUser::where(['user_id' => $user->id,'is_admin' => 1,'status' => 1 ])->first();

            if (!$isAdmin) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to add a task to this group.',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'due_date' => 'required|date',
                'created_date' => 'required|date',
                'description' => 'required',
                'cost' => 'nullable|numeric',
                'complete_note' => 'nullable',
                'event_id' => 'required|exists:events,id',
                'assigned_to' => 'required|exists:users,id',
            ],
            [
                'assigned_to.exists' => 'The selected user is not a valid member of the group.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Validation failed.',
                ], 422);
            }

            $assignedTo = $request->input('assigned_to');
            $groupUser = GroupUser::where(['user_id' => $assignedTo,'status' => 1])->first();
    
            if (!$groupUser) {
                return response()->json([
                    'status' => false,
                    'error' => [
                        'assigned_to' => 'The selected user is not a valid member of the group.'
                    ],
                    'message' => 'Validation failed.',
                ], 422);
            }

            $isAttendingEvent = EventAttendee::where('event_id', $request->event_id)
            ->where('user_id', $request->assigned_to)
            ->exists();
            if (!$isAttendingEvent) {
                return response()->json([
                    'status' => false,
                    'message' => 'The assigned user is not attending the event.',
                ], 422);
            }

            $taskData = $request->all();
            $taskData['status'] = 0;
    
            $task = Task::create($taskData);

            $assignedUser = User::find($assignedTo);

            $event = Event::find($request->event_id);

            if ($assignedUser) {
                $assignedUser->profile_img = $assignedUser->profile_img ? asset('images/profile') . '/' . $assignedUser->profile_img : null;
            }

            if($task){
                $this->notificationRepository->sendNotification($assignedUser->fcm_token, 'New! Task Assigned', 'Task ' . $task->name .' is assigned to you for event ' . $event->name);
            }

            $statusMapping = [
                0 => 'Pending',
                1 => 'In Progress',
                2 => 'Completed',
            ];

            $task['status'] = $statusMapping[$task->status];

            $task['user'] = $assignedUser;
            $task['is_attending'] = $isAttendingEvent;
            $task['event'] = $event;

            return response()->json([
                'status' => true,
                'message' => 'Task added successfully.',
                'data' => $task,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add task. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    function add_unassigned(Request $request) {
        try {
            $user = auth()->user();
            $isAdmin = GroupUser::where(['user_id' => $user->id,'is_admin' => 1,'status' => 1 ])->first();

            if (!$isAdmin) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to add a task to this group.',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'due_date' => 'required|date',
                'created_date' => 'required|date',
                'description' => 'required',
                'cost' => 'nullable|numeric',
                'complete_note' => 'nullable',
                'event_id' => 'required|exists:events,id',
                // 'assigned_to' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Validation failed.',
                ], 422);
            }

            $taskData = $request->all();
            $taskData['status'] = 5;
    
            $task = Task::create($taskData);

            $event = Event::find($request->event_id);

            if($task){
                // $this->notificationRepository->sendNotification($assignedUser->fcm_token, 'New! Task Assigned', 'Task ' . $task->name .' is assigned to you for event ' . $event->name);
            }

            $statusMapping = [
                0 => 'Pending',
                1 => 'In Progress',
                2 => 'Completed',
                5 => 'ُUnassigned',
            ];

            $task['status'] = $statusMapping[$task->status];

            $task['user'] = 0;
            $task['is_attending'] = 0;
            $task['event'] = $event;

            return response()->json([
                'status' => true,
                'message' => 'Task added successfully.',
                'data' => $task,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add task. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    function assign_task(Request $request, $Id){
        try {
            $user = auth()->user();
            $task = Task::find($Id);
            if (!$task) {
                return response()->json([
                    'status' => false,
                    'message' => 'Task not found.',
                ], 404);
            }
            // $isAdmin = GroupUser::where(['user_id' => $user->id,'is_admin' => 1,'status' => 1 ])->first();
            // if (!$isAdmin) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'You do not have permission to Assign a task to this group.',
            //     ], 403);
            // }
            $validator = Validator::make($request->all(), [
                'event_id' => 'required|exists:events,id',
                'assigned_to' => 'required|exists:users,id',
            ],
            [
                'assigned_to.exists' => 'The selected user is not a valid member of the group.'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Validation failed.',
                ], 422);
            }

            $assignedTo = $request->input('assigned_to');
            $groupUser = GroupUser::where(['user_id' => $assignedTo,'status' => 1])->first();
            if (!$groupUser) {
                return response()->json([
                    'status' => false,
                    'error' => [
                        'assigned_to' => 'The selected user is not a valid member of the group.'
                    ],
                    'message' => 'Validation failed.',
                ], 422);
            }
            $isAttendingEvent = EventAttendee::where('event_id', $request->event_id)
            ->where('user_id', $request->assigned_to)
            ->exists();
            if (!$isAttendingEvent) {
                return response()->json([
                    'status' => false,
                    'message' => 'The assigned user is not attending the event.',
                ], 422);
            }
            $taskData = $request->all();
            $taskData['status'] = 0;
            $task->update($taskData);
            $assignedUser = User::find($assignedTo);
            $event = Event::find($request->event_id);
            if ($assignedUser) {
                $assignedUser->profile_img = $assignedUser->profile_img ? asset('images/profile') . '/' . $assignedUser->profile_img : null;
            }
            if($task){
                $this->notificationRepository->sendNotification($assignedUser->fcm_token, 'New! Task Assigned', 'Task ' . $task->name .' is assigned to you for event ' . $event->name);
            }
            $statusMapping = [
                0 => 'Pending',
                1 => 'In Progress',
                2 => 'Completed',
                5 => 'Unassigned',
            ];
            $task['status'] = $statusMapping[$task->status];

            $task['user'] = $assignedUser;
            $task['is_attending'] = $isAttendingEvent;
            $task['event'] = $event;
            return response()->json([
                'status' => true,
                'message' => 'Task Assigned successfully.',
                'data' => $task,
            ], 201);
            

        }  catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to Assign task. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
    public function update(Request $request, $Id)
    {
        try {
            $user = auth()->user();
            
            $task = Task::find($Id);
            if (!$task) {
                return response()->json([
                    'status' => false,
                    'message' => 'Task not found.',
                ], 404);
            }
            
            $event = Event::find($task->event_id);
            
            $isAdmin = GroupUser::where(['group_id' => $event->id,'user_id' => $user->id, 'is_admin' => 1, 'status' => 1])->exists();

            if (!$isAdmin && ($task->assigned_to != $user->id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to update the task.',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'nullable',
                'due_date' => 'nullable|date',
                'created_date' => 'nullable|date',
                'description' => 'nullable',
                'cost' => 'nullable|numeric',
                'complete_note' => 'nullable',
                'status' => 'nullable|string|in:Pending,In Progress,Completed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Validation failed.',
                ], 422);
            }

            $statusMapping = [
                'Pending' => 0,
                'In Progress' => 1,
                'Completed' => 2,
                'Unassigned' => 5,
            ];
    
            $request['status'] = $statusMapping[$request->status];
            
            $task->update($request->all());

            $assignedUser = User::find($task->assigned_to);

            if ($assignedUser) {
                $assignedUser->profile_img = $assignedUser->profile_img ? asset('images/profile') . '/' . $assignedUser->profile_img : null;
            }

            $task['user'] = $assignedUser;
            $task['event'] = $event;

            return response()->json([
                'status' => true,
                'message' => 'Task updated successfully.',
                'data' => $task,
            ], 200);
        } catch (\Exception $e) {
           return response()->json([
    'status' => false,
    'message' => 'Failed to update task. Please try again later.',
    'error' => $e->getMessage(),
    'exception' => [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ],
], 500);
        }
    }
    

    public function delete(Request $request)
    {
        try {
            $user = auth()->user();
            $isAdmin = GroupUser::where(['user_id' => $user->id, 'is_admin' => 1, 'status' => 1])->first();

            if (!$isAdmin) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to delete tasks.',
                ], 403);
            }

            $taskId = $request->input('task_id');
            $task = Task::find($taskId);
            
            if (!$task) {
                return response()->json([
                    'status' => false,
                    'message' => 'Task not found.',
                ], 404);
            }

            $task->delete();

            return response()->json([
                'status' => true,
                'message' => 'Task deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getSpecificUserTasks()
    {
        try {
            $user = auth()->user();
            $specificUser = User::find($user->id);
            if($specificUser){
                $specificUser->profile_img = $specificUser->profile_img ? asset('images/profile') . '/' . $specificUser->profile_img : null;
            }
            
            $tasks = Task::where('assigned_to', $user->id)->get();

            foreach ($tasks as $task) {
                $event = Event::find($task->event_id);
            }
    
            if ($tasks->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No tasks found.',
                ], 404);
            }
    
            $statusMapping = [
                0 => 'Pending',
                1 => 'In Progress',
                2 => 'Completed',
            ];
            $tasks = $tasks->map(function ($task) use ($statusMapping) {
                $task['status'] = $statusMapping[$task->status];
                return $task;
            });
    
            $responseData = [
                'assigned_user' => $specificUser,
                'event' => $event,
                'tasks' => $tasks
            ];
    
            return response()->json([
                'status' => true,
                'message' => 'Tasks fetched successfully.',
                'data' => $responseData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch tasks.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
