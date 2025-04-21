<?php

namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use App\Interfaces\GroupRepositoryInterface;
use App\Interfaces\NotificationRepositoryInterface;
use App\Models\Group;
use App\Models\Household;
use App\Models\Event;
use App\Models\GroupUser;
use App\Models\GroupHousehold;
use App\Models\HouseholdApproval;
use App\Models\HouseholdUser;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Providers\Auth\Illuminate;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    private GroupRepositoryInterface $groupRepository;
    private NotificationRepositoryInterface $notificationRepository;

    public function __construct(GroupRepositoryInterface $groupRepository, NotificationRepositoryInterface $notificationRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function get()
    {
        try {
            $groups = Group::all();
            if ($groups->isEmpty()) {
                return response()->json(['status' => false, 'error' => 'No group records found.'], 404);
            }

            $groupData = $groups->map(function ($group) {
                $group->profile_img = $group->profile_img = isset($group->profile_img) ? asset('images/groups') . '/' . $group->profile_img : null;
                $group['members'] = $this->groupRepository->getGroupMemberProfileData($this->groupRepository->getGroupMembers($group->id));
                return $group;
            });

            return response()->json([
                'status' => true,
                'data' => $groupData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => 'Error retrieving groups.'], 500);
        }
    }


    // public function getyour()
    // {

    //     try {
    //         $userId = auth()->id(); // or pass the user ID as a parameter to the method

    //         $groups = Group::whereHas('users', function ($query) use ($userId) {
    //             $query->where('user_id', $userId);
    //         })->get();

    //         if ($groups->isEmpty()) {
    //             return response()->json(['status' => false, 'error' => 'No group records found.'], 404);
    //         }

    //         $groupData = $groups->map(function ($group) {
    //             $group->profile_img = isset($group->profile_img) ? asset('images/groups') . '/' . $group->profile_img : null;
    //             $group['members'] = $this->groupRepository->getGroupMemberProfileData($this->groupRepository->getGroupMembers($group->id));
    //             return $group;
    //         });

    //         return response()->json([
    //             'status' => true,
    //             'data' => $groupData,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => false, 'error' => 'Error retrieving groups.'], 500);
    //     }

    // }


    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'profile_img' => 'required|image|mimes:jpeg,png,jpg,gif',
                'description' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'error' => $validator->errors()], 422);
            }

            $data = $request->all();
            if ($request->hasFile('profile_img')) {
                $file = $request->file('profile_img');
                $fileName = $file->getClientOriginalName();
                $file->move('images/groups', $fileName);
                $data['profile_img'] = '' . $fileName;
            }
            $randomString = Str::random(10);
            $timestampString = Carbon::now()->timestamp;
            $data['serial_id'] = $randomString . $timestampString;
            $data['creator_id'] = $user->id;
            $group = Group::create($data);

            GroupUser::create(['user_id' => $user->id, 'group_id' => $group->id, 'is_admin' => true]);

            if ($group) {
                $group->profile_img = asset('images/groups') . '/' . $group->profile_img;
            }
            return response()->json([
                'status' => true,
                'message' => 'Group created successfully',
                'data' => $group
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }
    }


    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string',
                'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'description' => 'string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                ], 422);
            }

            $group = Group::find(auth()->id());
            if (!$group) {
                return response()->json([
                    'status' => false,
                    'error' => 'group not found.'
                ], 404);
            }

            $oldProfileImg = $group->profile_img;
            $group->update($request->all());
            if ($request->hasFile('profile_img')) {
                $file = $request->file('profile_img');
                $fileName = $file->getClientOriginalName();
                $file->move('images/groups', $fileName);

                if ($oldProfileImg && file_exists(public_path('images/groups/' . $oldProfileImg))) {
                    unlink(public_path('images/groups/' . $oldProfileImg));
                }
                $group->update(['profile_img' => $fileName]);
                if ($group) {
                    $group->profile_img = $group->profile_img ? asset('images/groups') . '/' . $group->profile_img : null;
                }
            }
            $user = auth()->user();
            GroupUser::updateOrCreate(['user_id' => $user->id, 'group_id' => $group->id]);
            return response()->json([
                'status' => true,
                'message' => 'Group updated successfully',
                'group' => $group
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }
    }

    public function show($id)
    {
        try {
            $userId = auth()->user()->id;

            $userHousehold = auth()->user()->userHousehold;

            $group = Group::find($id);

            if (!$group) {
                return response()->json([
                    'status' => false,
                    'error' => 'group not found.'
                ], 404);
            }

            $userGroup = GroupUser::where(['user_id' => $userId, 'group_id' => $id])->first();

            $ShowLabels = GroupUser::where(['user_id' => $userId, 'group_id' => $id, 'status' => 1])->exists();

            if ($userHousehold) {
                $groupHousehold = GroupHousehold::where('household_id', $userHousehold->household_id)->first();
            }

            $events = Event::where('group_id', $id)->get();

            $group->profile_img = $group->profile_img = isset($group->profile_img) ? asset('images/groups') . '/' . $group->profile_img : null;

            return response()->json([
                'status' => true,
                'data' => [
                    "group" => $group,
                    "members" => $this->groupRepository->getGroupMemberProfileData($this->groupRepository->getGroupMembers($group->id)),
                    "is_group_admin" => $userGroup ? $userGroup->is_admin : null,
                    'households' => $this->groupRepository->getApprovedMembersList($group->id),
                    "events" => $events,
                    "show_labels" => $ShowLabels,
                    "individual_join_request_status" => $userGroup ? $userGroup->status : null,
                    "household_join_request_status" => $groupHousehold ? $groupHousehold->status : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Error retrieving group.'
            ], 500);
        }
    }

    public function joinRequestByIndividualMember(Request $request)
    {
        try {
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'group_id' => 'required|exists:groups,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                ], 422);
            }

            $group = Group::find($request->group_id);
            if (!$group) {
                return response()->json([
                    'status' => false,
                    'error' => 'Group not found.'
                ], 404);
            }

            $isHouseholdMember = HouseholdUser::where('user_id', $user->id)
                ->where('status', 1)
                ->exists();
            $isHouseholdAdmin = HouseholdUser::where('user_id', $user->id)
                ->where('status', 1)
                ->first();

            $haveHousehold = HouseholdUser::where('user_id', $user->id)
                ->where('status', 1)
                ->where('is_admin', 1)
                ->first();

            if (!$isHouseholdMember) {
                return response()->json([
                    'status' => false,
                    'error' => 'You must be an approved household member to join the group.',
                ], 403);
            }

            if($haveHousehold){
                $existingRequest = GroupUser::where('user_id', $user->id)
                ->where('group_id', $request->group_id)
                ->whereIn('status', [0, 3])
                ->exists();
            }else{
                $existingRequest = HouseholdApproval::where('user_id', $user->id)
                ->where('group_id', $request->group_id)
                ->exists();
            }

                  
            if ($existingRequest) {
                return response()->json([
                    'status' => false,
                    'error' => 'Join request for this group already exists.',
                ], 400);
            }
            if (!$user->dob) {
                return response()->json([
                    'status' => false,
                    'error' => 'Please fill your missing profile information first.',
                ], 422);
            }
            // Check if the user is under 18
            $userAge = now()->diffInYears($user->dob);

            $status = ($isHouseholdAdmin->is_admin) ? 0 : ($userAge < 18 ? 3 : 0);

            if($haveHousehold){
                $data = [
                    'user_id' => $user->id,
                    'group_id' => $request->group_id,
                    'is_admin' => 0,
                    'status' => $status,
                ];
            
            }else{
                $data = [
                    'user_id' => $user->id,
                    'group_id' => $request->group_id,
                ];
            }
           

            $groupAdmin = GroupUser::where(['group_id' => $request->group_id, 'is_admin' => 1, 'status' => 1])->first();

            if ($this->groupRepository->isGroupUserPresent($user->id, 0)) {
                if($haveHousehold){
                    $isUpdated = GroupUser::where('user_id', $user->id)->update(['group_id' => $request->group_id, 'is_admin' => 0, 'status' => 0]);
                }else{

                    $isUpdated =  HouseholdApproval::where('user_id', $user->id)->update(['group_id' => $request->group_id ]);
                }

                if ($isUpdated) {
                    $this->notificationRepository->sendNotification($groupAdmin->user->fcm_token, 'New! Group Join Request', $user->username . ' requested to join ' . $group->name);
                }
            } else {
                if($haveHousehold){
                    $createdRequest = GroupUser::create($data);
                }else{
                    $createdRequest = HouseholdApproval::create($data);
                }

                if ($createdRequest) {
                    $this->notificationRepository->sendNotification($groupAdmin->user->fcm_token, 'New! Group Join Request', $user->username . ' requested to join ' . $group->name);
                }
            }

            if ($group->profile_img) {
                $group->profile_img = $group->profile_img ? asset('images/groups') . '/' . $group->profile_img : null;
            }
            $group['user'] = $user;
            if($haveHousehold)
            {
              $message =  'Group join is requested by user';
            }else{
              $message  =  'You are under 18. You need age approval first from your household admin for group join.';
            }
            return response()->json([
                'status' => true,
                'message' => ($isHouseholdAdmin && $isHouseholdAdmin->is_admin)
                    ? 'Group join request has been sent.'
                    : ($userAge < 18
                        ? $message
                        : 'Group join is requested by user'),
                'group' => $group
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->errors(),
                'message' => 'Failed.'
            ], 422);
        }
    }

    public function requestForApproval(Request $request)
    {
        try {
            $user = auth()->user();
            
            $isAdmin = DB::table('household_approvals')
            ->join('users','household_approvals.user_id','=','users.id')
            ->join('groups','household_approvals.group_id','=','groups.id')
            ->join('household_user as hu1', 'hu1.user_id', '=', 'household_approvals.user_id')
            ->join('household_user as hu2', 'hu2.household_id', '=', 'hu1.household_id')
            ->where('hu2.is_admin', 1)
            ->where('hu2.user_id' ,$user->id)
            ->select('household_approvals.*','users.email','groups.name')
            ->get();


            if ($isAdmin->IsEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No join requests found.',
                ], 404);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Join requests fetched successfully.',
                    'data' => $isAdmin ,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch join requests.',
            ], 500);
        }
    }

    public function householdAdminApprove(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors(),
            ], 422);
        }

       $data = HouseholdApproval::find($request->id);

       if(!$data){
        return response()->json([
            'status' => false,
            'message' => 'No approval requests found with this id',
        ], 404);
       }

       $createdRequest = GroupUser::create([
        'user_id' => $data->user_id,
        'group_id' => $data->group_id,
        'is_admin' => 0,
        'status' => 0,    
       ]);

     if($createdRequest){
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'approved by admin',
        ], 200);
     }
     
    }
    public function householdAdmindisapprove(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors(),
            ], 422);
        }

       $data = HouseholdApproval::find($request->id);

       if(!$data){
        return response()->json([
            'status' => false,
            'message' => 'No approval requests found with this id',
        ], 404);
       }
   
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Request rejected ',
        ], 200);
    
     
    }
    
    
    public function getIndividualMemberJoinRequests()
    {
        try {
            $user = auth()->user();
            $isAdmin = GroupUser::where('is_admin', true)->where('user_id', $user->id)->get();
            if ($isAdmin->IsEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to view join requests.',
                ], 403);
            }

            $groups = GroupUser::where(['group_id' => $isAdmin[0]->group_id, 'is_admin' => 0, 'status' => 0])->get();

            if ($groups->IsEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No join requests found.',
                ], 404);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Join requests fetched successfully.',
                    'data' => $this->groupRepository->getGroupMemberData($groups),
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch join requests.',
            ], 500);
        }
    }

    public function updateIndividualMemberJoinRequestStatus(Request $request)
    {
        try {
            $user = auth()->user();
            $groupUser = GroupUser::where('is_admin', true)->where('user_id', $user->id)->first();
            if (!$groupUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to update join request status',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'request_id' => 'required|exists:group_user,id',
                'status' => 'required|in:Approved,Rejected',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->toArray(),
                    'message' => 'Validation failed.',
                ], 422);
            }

            $statusMapping = [
                'Approved' => 1,
                'Rejected' => 2,
            ];

            $data = GroupUser::where('id', $request->request_id)
                ->update(['status' => $statusMapping[$request->status]]);

            return response()->json([
                'status' => true,
                'message' => 'Join request status updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update join request status.',
            ], 500);
        }
    }
    function updateIndividualMemberGroupAdmin(Request $request)
    {
        try {
            $user = auth()->user();

            $isadmin = GroupUser::where('is_admin', true)->where('user_id', $user->id)->first();
            if (!$isadmin) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to update join request status',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'group_id' => 'required|exists:groups,id',
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->toArray(),
                    'message' => 'Validation failed.',
                ], 422);
            }
            $groupUser = GroupUser::where(['group_id' => $request->group_id, 'user_id' => $request->user_id])->first();
            if (!$groupUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Users not in Group',
                ], 422);
            }
            $groupUser->update(['is_admin' => true]);
            return response()->json([
                'status' => true,
                'message' => 'Users Updated as Admin of Group.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update User as group admin.',
            ], 500);
        }
    }
    public function getIndividualMemberGroupsList()
    {
        try {
            $user = auth()->user();
            $individualMemberGroups = GroupUser::where(['user_id' => $user->id, 'is_admin' => 0, 'status' => 1])->get();

            if ($individualMemberGroups->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No groups found for the individual member.',
                ], 404);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Individual member groups fetched successfully.',
                    'data' => $this->groupRepository->getGroupMemberData($individualMemberGroups),
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch individual member groups.',
            ], 500);
        }
    }

    public function getApprovedGroupMembers(Request $request)
    {
        try {
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'group_id' => 'required|exists:groups,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                ], 422);
            }

            $groupId = $request->input('group_id');

            $isAdmin = GroupUser::where(['is_admin' => true, 'user_id' => $user->id, 'group_id' => $groupId])->first();

            if (!$isAdmin) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to view approved group members.',
                ], 403);
            }

            $approvedMembers = GroupUser::where(['group_id' => $groupId, 'status' => 1])->get();

            if ($approvedMembers->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No approved group members found.',
                ], 404);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Approved group members fetched successfully.',
                    'data' => $this->groupRepository->getGroupMemberData($approvedMembers),
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch approved group members.',
            ], 500);
        }
    }


    public function joinRequestByHousehold(Request $request)
    {
        try {
            $user = auth()->user();
            $isAdmin = HouseholdUser::where('is_admin', true)->where('user_id', $user->id)->get();
            if ($isAdmin->IsEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to request for join.',
                ], 403);
            }
            $validator = Validator::make($request->all(), [
                'group_id' => 'required|exists:groups,id',
                'household_id' => 'required|exists:households,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                ], 422);
            }

            $group = Group::find($request->group_id);
            $household = Household::find($request->household_id);

            if (!$group) {
                return response()->json([
                    'status' => false,
                    'error' => 'Group not found.'
                ], 404);
            }
            if (!$household) {
                return response()->json([
                    'status' => false,
                    'error' => 'Household not found.'
                ], 404);
            }

            $existingRelation = GroupHousehold::where('household_id', $request->household_id)
                ->where('group_id', $request->group_id)
                ->where('status', 0)
                ->first();

            if ($existingRelation) {
                return response()->json([
                    'status' => false,
                    'error' => "The household is already associated with the group.",
                ], 400);
            }

            $groupAdmin = GroupUser::where(['user_id' => $user->id, 'group_id' => $request->group_id, 'is_admin' => 1])->exists();



            if ($groupAdmin) {
                $data = [
                    'household_id' => $request->household_id,
                    'group_id' => $request->group_id,
                    'status' => 1
                ];
            } else {
                $data = [
                    'household_id' => $request->household_id,
                    'group_id' => $request->group_id,
                    'status' => 0
                ];
            }

            $isRequested = GroupHousehold::updateOrCreate(['household_id' => $request->household_id, 'group_id' => $request->group_id], $data);

            if ($isRequested) {
                $groupAdmin = GroupUser::where(['group_id' => $request->group_id, 'is_admin' => 1, 'status' => 1])->first();
                $this->notificationRepository->sendNotification($groupAdmin->user->fcm_token, 'New! Household Join Request', $household->name . ' household requested to connect with ' . $group->name);
            }

            if ($group->profile_img) {
                $group->profile_img = $group->profile_img ? asset('images/groups') . '/' . $group->profile_img : null;
            }
            $data['group'] = $group;
            $data['household'] = $household;
            $data['user'] = $user;

            return response()->json([
                'status' => true,
                'message' => 'Join request sent by household to join the group',
                'group' => $data
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->errors(),
                'message' => 'Failed.'
            ], 422);
        }
    }

    public function getHouseholdJoinRequests()
    {
        try {
            $user = auth()->user();
            $isAdmin = GroupUser::where('is_admin', true)->where('user_id', $user->id)->get();
            if ($isAdmin->IsEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to view join requests.',
                ], 403);
            }

            $groupIds = $isAdmin->pluck('group_id')->toArray();

            $grouphouseholds = GroupHousehold::whereIn('group_id', $groupIds)
                ->where('status', 0)
                ->get();

            $grouphouseholds->map(function ($grouphousehold) {
                $group = Group::find($grouphousehold->group_id);
                $group->profile_img = $group->profile_img ? asset('images/groups') . '/' . $group->profile_img : null;
                $grouphousehold['group'] = $group;

                $household = Household::find($grouphousehold->household_id);
                $household->profile_img = $household->profile_img ? asset('images/households') . '/' . $household->profile_img : null;
                $grouphousehold['household'] = $household;
            });

            if ($grouphouseholds->IsEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No join requests found.',
                ], 404);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Join requests fetched successfully.',
                    'data' => $grouphouseholds
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch join requests.',
            ], 500);
        }
    }

    public function updateHouseholdJoinRequestStatus(Request $request)
    {
        try {
            $user = auth()->user();
            $groupUser = GroupUser::where('is_admin', true)->where('user_id', $user->id)->first();
            if (!$groupUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to update join request status',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'request_id' => 'required|exists:group_household,id',
                'status' => 'required|in:Approved,Rejected',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->toArray(),
                    'message' => 'Validation failed.',
                ], 422);
            }

            $statusMapping = [
                'Approved' => 1,
                'Rejected' => 2,
            ];

            GroupHousehold::where('id', $request->request_id)
                ->update(['status' => $statusMapping[$request->status]]);

            $groupHousehold = GroupHousehold::find($request->request_id);

            if ($groupHousehold) {
                $user_ids = HouseholdUser::where('household_id', $groupHousehold->household_id)->pluck('user_id')->toArray();

                GroupUser::whereIn('user_id', $user_ids)->where('is_admin', 0)->delete();
            }
        
            $getuserfromhousehold =HouseholdUser::where('household_id', $groupHousehold->household_id)
            ->where('is_admin',1)->first();

            $checkuser =  GroupUser::where('user_id' ,$getuserfromhousehold->user_id )
            ->where('group_id' , $groupHousehold->group_id )->first(); 

            if(!$checkuser){
                GroupUser::create([
                    'user_id' => $getuserfromhousehold->user_id,
                    'group_id' => $groupHousehold->group_id ,
                    'is_admin' => 0,
                    'status' => 1,    
                   ]);
            }else{
                $checkuser =  GroupUser::where('user_id' , $getuserfromhousehold->user_id )
                ->where('group_id' ,$groupHousehold->group_id )->update(['status' => $statusMapping[$request->status]]); 
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Join request status updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update join request status.',
            ], 500);
        }
    }

    public function getGroupHouseholdsList(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'group_id' => 'required|exists:groups,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                ], 422);
            }

            $groupId = $request->input('group_id');
            $user = auth()->user();

            $isAdmin = GroupUser::where(['is_admin' => true, 'user_id' => $user->id, 'group_id' => $request->group_id])->first();

            if (!$isAdmin) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to view group households.',
                ], 403);
            }

            return response()->json([
                'status' => true,
                'message' => 'Group households fetched successfully.',
                'data' => $this->groupRepository->getApprovedMembersList($groupId)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch group households.',
            ], 500);
        }
    }

    public function getApprovedHouseholdMembers(Request $request)
    {
        try {
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'group_id' => 'required|exists:groups,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                ], 422);
            }

            $groupId = $request->input('group_id');
            $isAdmin = GroupUser::where(['is_admin' => true, 'user_id' => $user->id, 'group_id' => $groupId])->first();

            if (!$isAdmin) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to view approved households.',
                ], 403);
            }

            $approvedHouseholds = GroupHousehold::where(['group_id' => $groupId, 'status' => 1])->get();

            if ($approvedHouseholds->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No approved households found.',
                ], 404);
            } else {
                $statusMapping = [
                    0 => 'Pending',
                    1 => 'Approved',
                    2 => 'Rejected',
                ];

                $groupDetails = Group::find($groupId);
                $householdIds = $approvedHouseholds->pluck('household_id')->toArray();
                $householdDetails = Household::whereIn('id', $householdIds)->get();

                $approvedHouseholds = $approvedHouseholds->map(function ($item) use ($statusMapping) {
                    $item['status'] = $statusMapping[$item['status']];
                    return $item;
                });

                return response()->json([
                    'status' => true,
                    'message' => 'Approved households fetched successfully.',
                    'data' => [
                        'group' => $groupDetails ? $groupDetails->toArray() : null,
                        'approved_households' => $approvedHouseholds->toArray(),
                        'households' => $householdDetails->toArray(),
                    ],
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch approved households.',
            ], 500);
        }
    }

    public function getUserJoinedGroups()
    {
        $user = auth()->user();
        $joinedGroups = GroupUser::where('user_id', $user->id)->pluck('group_id')->toArray();

        $groups = Group::whereIn('id', [$joinedGroups])->get();

        $groups->map(function ($group) {
            $group->profile_img = $group->profile_img = isset($group->profile_img) ? asset('images/groups') . '/' . $group->profile_img : null;
            return $group;
        });

        return response()->json([
            'status' => true,
            'message' => 'Joined Groups',
            'data' => $groups,
        ], 200);
    }

    public function getUserNotJoinedGroups()
    {
        $user = auth()->user();

        $joinedGroups = GroupUser::where('user_id', $user->id)->pluck('group_id')->toArray();

        $groups = Group::whereNotIn('id', [$joinedGroups])->get();

        $groups->map(function ($group) {
            $group->profile_img = $group->profile_img = isset($group->profile_img) ? asset('images/groups') . '/' . $group->profile_img : null;
            return $group;
        });

        return response()->json([
            'status' => true,
            'message' => 'Not Joined Groups',
            'data' => $groups,
        ], 200);
    }
}
