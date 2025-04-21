<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Interfaces\HouseholdRepositoryInterface;
use App\Models\User;
use App\Models\Event;
use App\Models\GroupHousehold;
use App\Models\GroupUser;
use App\Models\HouseholdHousehold;
use App\Models\HouseholdUser;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private HouseholdRepositoryInterface $householdRepository;

    public function __construct(HouseholdRepositoryInterface $householdRepository)
    {
        $this->householdRepository = $householdRepository;
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'string|unique:users,username,' . auth()->id(),
                'fullname' => 'string',
                'email' => 'email',
                'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'address' => 'string',
                'dob' => 'nullable|date',
                'gender' => 'string',
                'country_code' => 'string',
                'phone' => 'string'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'error' => $validator->errors()], 422);
            }

            $user = auth()->user();

            $data = $validator->validated();

            if ($request->hasFile('profile_img')) {
                $oldImage = $user->profile_img;
                if ($oldImage && file_exists(public_path('images/profile/' . $oldImage))) {
                    unlink(public_path('images/profile/' . $oldImage));
                }
                $file = $request->file('profile_img');
                $fileName = $file->getClientOriginalName();
                $file->move('images/profile', $fileName);
                $data['profile_img'] = $fileName;
            }

            $user->update($data);

            if ($user) {
                if (isset($data['profile_img'])) {
                    $user['profile_img'] = asset('images/profile') . '/' . $data['profile_img'];
                }
                return response()->json([
                    'status' => true,
                    'message' => 'User updated successfully',
                    'user' => $user,
                ]);
            } else {
                return response()->json(['status' => false, 'error' => 'Failed user update'], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Error updating user: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getuser(Request $request)
    {
        try {
            $user = User::find(auth()->id());

            if (!$user) {
                return response()->json(['status' => false, 'error' => 'User not found.'], 404);
            }

            if ($user->profile_img) {
                $user['profile_img'] = asset('images/profile') . '/' . $user->profile_img;
            }
            return response()->json([
               'status' => true,
                'user' => $user,
            ],200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Error fetching user: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function userdetail(Request $request, $id) {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['status' => false, 'error' => 'User not found.'], 404);
        }

        if ($user->profile_img) {
            $user->profile_img = asset('images/profile') . '/' . $user->profile_img;
        }
    
        return response()->json([
            'status' => true,
            'data' => $user
        ]);

    }    


    public function show()
    {
        try {
            $user = User::find(auth()->id());

            if (!$user) {
                return response()->json(['status' => false, 'error' => 'User not found.'], 404);
            }

            if ($user->profile_img) {
                $user['profile_img'] = asset('images/profile') . '/' . $user->profile_img;
            }

            $userHouseholdDetails = isset($user->userHousehold) ? $user->userHousehold->household : null;

            if (isset($userHouseholdDetails->profile_img)) {
                $userHouseholdDetails->profile_img = asset('images/households') . '/' . $userHouseholdDetails->profile_img;
            }

            $userGroups = GroupUser::where(['user_id' => $user->id, 'status' => 1])->pluck('group_id')->toArray();

            $userId = auth()->id(); 
            $allGroups = Group::whereDoesntHave('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();

            $isuseradmin = HouseholdUser::where('is_admin', 1)->where('user_id',$userId)->first();

            $grouphouseholdcount = 0 ;

            if($isuseradmin){
 
               $grouphouseholdcount =  GroupHousehold::where('household_id',$isuseradmin->household_id )
                 ->where('status' , 1)->count();

                $householdJoinRequestCount = HouseholdUser::where('status', 0)->where('household_id',$isuseradmin->household_id)->count();
            }else{
                $householdJoinRequestCount = HouseholdUser::where('status', 0)->where('user_id',$userId)->count();
            }

            $gethousehold = HouseholdUser::where('user_id',$userId)->first();     
            if($gethousehold){
                $connectionRequestCount = HouseholdHousehold::where('status', 0)->
                where('requested_household_id', $gethousehold->household_id)->orwhere('household_id', $gethousehold->household_id) ->count();
            }else{
                $connectionRequestCount = 0 ;
            }

            $upcomingEvents = Event::whereIn('group_id', $userGroups)->where('start_date', '>=', date('Y-m-d H:i:s'))->pluck('id')->toArray();
            $pendingTasks = Task::whereIn('event_id', $upcomingEvents)->where('status', 0)->count();
            $individualGroupJoinRequestCount = GroupUser::whereIn('group_id', $userGroups)->where('status', 0)->count();
            $underGroupJoinRequestCount = GroupUser::whereIn('group_id', $userGroups)->where('status', 3)->count();
            $householdGroupJoinRequest = GroupHousehold::whereIn('group_id', $userGroups)->where('status', 0)->count();

            if($grouphouseholdcount > 0)
            {
                $allusergroups =  count($userGroups) +  $grouphouseholdcount ;
            }
            else{
                $allusergroups = count($userGroups);
            }

            $data = [
                'user' => $user,
                'householdMembers' => isset($userHouseholdDetails->id) ? $this->householdRepository->getHouseholdMemberProfileData($this->householdRepository->getHouseholdMembers($userHouseholdDetails->id)) : null,
                'householdAdmin' => isset($user->userHousehold) ? $user->userHousehold->is_admin : null,
                'userHouseholdStatus' => isset($user->userHousehold) ? $user->userHousehold->status : null,
                'totalJoinedGroups' => $allusergroups ,
                'totalGroups' => count($allGroups),
                'totalUpcomingEvents' => count($upcomingEvents),
                'pendingTasks' => $pendingTasks,
                'householdJoinRequestCount' => $householdJoinRequestCount,
                'connectionRequestCount' => $connectionRequestCount,
                'individualGroupJoinRequestCount' => $individualGroupJoinRequestCount,
                'underGroupJoinRequestCount' => $underGroupJoinRequestCount,
                'householdGroupJoinRequest' => $householdGroupJoinRequest,

            ];

            return response()->json([
                'status' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Error fetching user: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getUserById($Id)
    {
        try {
            $user = User::find($Id);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            if ($user->profile_img) {
                $user->profile_img = asset('images/profile') . '/' . $user->profile_img;
            }

            $userHouseholdDetails = isset($user->userHousehold) ? $user->userHousehold->household : null;
            // $userGroupDetails = isset($user->userGroup) ? $user->userGroup->group : null;
            // $userTaskDetails = isset($user->userTasks) ? $user->userTasks[0]->tasks : null;
            // $userEventDetails = Event::where('group_id', $user->userGroup->group_id)->get();

            if (isset($userHouseholdDetails->profile_img)) {
                $userHouseholdDetails->profile_img = asset('images/households') . '/' . $userHouseholdDetails->profile_img;
            }
            // if (isset($userGroupDetails->profile_img)) {
            //     $userGroupDetails->profile_img = asset('images/groups') . '/' . $userGroupDetails->profile_img;
            // }

            $data = [
                'user' => $user,
                'householdMembers' => isset($userHouseholdDetails->id) ? $this->householdRepository->getHouseholdMemberProfileData($this->householdRepository->getHouseholdMembers($userHouseholdDetails->id)) : null,
            ];

            return response()->json([
                'status' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Error fetching user: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getsingleUserById($Id)
    {
        try {
            $user = User::find($Id);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            if ($user->profile_img) {
                $user->profile_img = asset('images/profile') . '/' . $user->profile_img;
            }

            return response()->json([
                'status' => true,
                'data' => $user 
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Error fetching user: ' . $e->getMessage(),
            ], 500);
        }
    }
}