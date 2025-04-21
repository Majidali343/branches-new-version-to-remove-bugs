<?php

namespace App\Http\Controllers\Api\Household;

use App\Http\Controllers\Controller;
use App\Interfaces\HouseholdRepositoryInterface;
use App\Interfaces\NotificationRepositoryInterface;
use App\Models\Household;
use App\Models\HouseholdHousehold;
use Illuminate\Support\Facades\Validator;
use App\Models\HouseholdUser;
use App\Models\GroupUser;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\State;

class HouseholdController extends Controller
{

    private HouseholdRepositoryInterface $householdRepository;
    private NotificationRepositoryInterface $notificationRepository;

    public function __construct(HouseholdRepositoryInterface $householdRepository, NotificationRepositoryInterface $notificationRepository)
    {
        $this->householdRepository = $householdRepository;
        $this->notificationRepository = $notificationRepository;
    }
    public function leave(Request $request){
        try {
            $user = auth()->user();
            $userid = $user->id;

            // Check if the user is an admin of the household
            $existHouseholdUser = HouseholdUser::where(['user_id' => $userid, 'is_admin' => '1'])->first();
           

            if (isset($existHouseholdUser)) {
                // Find another user in the household to promote to admin
                $newAdmin = HouseholdUser::where(['household_id' => $existHouseholdUser->household_id, 'is_admin' => '0', 'status'=> 1])->first();

                if ($newAdmin ) {
                    // Promote the new user to admin
                    $newAdmin->is_admin = '1';
                    $newAdmin->save();
                }else{
                    
                    Household::where('id', $existHouseholdUser->household_id)->delete();
                    
                }

                // Delete the current admin user
                $existHouseholdUser->delete();
    
                return response()->json([
                    'status' => true,
                    'message' => 'You have left the household and a new admin has been promoted.'
                ], 200);
            } else {
               
            return response()->json(['status' => false, 'error' => 'User is not admin of the household.'], 400);
                
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => 'Error leaving household.'], 500);
        }
    }
    

    public function get()
    {
        try {
            $households = Household::all();
    
            if ($households->isEmpty()) {
                return response()->json(['status' => false, 'error' => 'No household records found.'], 404);
            }
    
            $householdsData = $households->map(function ($household) {
                $household->profile_img = $household->profile_img = isset($household->profile_img) ? asset('images/households'). '/' . $household->profile_img : null;
                $household['members'] = $this->householdRepository->getHouseholdMemberProfileData($this->householdRepository->getHouseholdMembers($household->id));
                return $household;
            });
    
            return response()->json([
                'status' => true,
                'data' => $householdsData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => 'Error retrieving households.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = auth()->user();
           
            if(!$user->dob){
                return response()->json([
                    'status' => false,
                    'error' => 'Please fill your missing profile information first.',
                ], 422);
            }

            $userAge = now()->diffInYears($user->dob);
            if($userAge< 18){
                return response()->json([
                    'status' => false,
                    'error' => 'You are under 18 you can only join households',
                ], 422);
            }

            $existHouseholdUser = HouseholdUser::where('user_id', $user->id)->first();
            if(isset($existHouseholdUser) && $existHouseholdUser->is_admin){
                return response()->json([
                    'status' => false,
                    'message' => 'User already created household.',
                ], 403);
            }
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'profile_img' => 'required|image|mimes:jpeg,png,jpg,gif',
                'city' => 'required|string',
                'state' => 'required|string',
                'country' => 'required|string',
                'address' => 'required|string',
                'household_id' => 'required|string',
                'zip' => 'required|string',
                'household_bio' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'error' => $validator->errors()], 422);
            }
            
            $data = $request->all();
           

            if ($request->hasFile('profile_img')) {
                $file = $request->file('profile_img');
                $fileName = $file->getClientOriginalName();
                $file->move('images/households', $fileName);
                $data['profile_img'] = '' . $fileName;
            }

            $randomString = Str::random(10);
            $timestampString = Carbon::now()->timestamp;
            $data['serial_id'] =$randomString . $timestampString;
            $data['premium_expiry'] = now();

            $household = Household::create($data); 

            if(isset($existHouseholdUser)){
                $existHouseholdUser->update(['household_id' => $household->id, 'is_admin' => true]);
            }else{
                HouseholdUser::create(['user_id' => $user->id, 'household_id' => $household->id, 'is_admin' => true ,'status' => 1]);
            }

            if($household){
                $household->profile_img = asset('images/households'). '/' . $household->profile_img;
            }
            return response()->json([
                'status' => true,
                'message' => 'Household created successfully',
                'data' => $household
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
                'household_bio' => 'string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                ], 422);
            }

            $householduser = HouseholdUser::where('user_id', auth()->id())
            ->where('is_admin', 1)
            ->first();

            $household = Household::findOrFail($householduser->household_id);


            if (!$household) {
                return response()->json([
                    'status' => false,
                    'error' => 'Household not found.'
                ], 404);
            }

            $oldProfileImg = $household->profile_img;
            $household->update($request->all());
            if ($request->hasFile('profile_img')) {
                $file = $request->file('profile_img');
                $fileName = $file->getClientOriginalName();
                $file->move('images/households', $fileName);

                if ($oldProfileImg && file_exists(public_path('images/households/' . $oldProfileImg))) {
                    unlink(public_path('images/households/' . $oldProfileImg));
                }
                $household->update(['profile_img' => $fileName]);
                if ($household) {
                    $household['profile_img'] = asset('images/households') . '/' . $fileName;
                }
            }
            $user = auth()->user();
            HouseholdUser::updateOrCreate(['user_id' => $user->id, 'household_id' => $household->id]);
            return response()->json([
                'status' => true,
                'message' => 'Household updated successfully',
                'household' => $household
                ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }
    }

    public function show()
    {
        try {
            $userId = auth()->user()->id;
            $userHousehold = HouseholdUser::where('user_id', $userId)->first();
    
            if (empty($userHousehold)) {
                return response()->json([
                    'status' => false,
                    'error' => 'Household not found.'
                ], 404);
            }

            $household = $userHousehold->household;

            $isPremium = false;
            $premiumSubscription = null;
            if (!is_null($household->premium_expiry)) {
                $currentDateTime = Carbon::now();
                $premiumExpiryDateTime = Carbon::parse($household->premium_expiry);
            
                if ($premiumExpiryDateTime->gt($currentDateTime)) {
                    $isPremium = true;
                    // Calculate remaining duration in months and days
                    $remainingDuration = $premiumExpiryDateTime->diff($currentDateTime);
                    $months = $remainingDuration->m;
                    $days = $remainingDuration->d;
                    // Format remaining duration
                    $formattedDuration = '';
                    if ($months > 0) {
                        $formattedDuration .= $months . ' months';
                        if ($days > 0) {
                            $formattedDuration .= ' and ';
                        }
                    }
                    if ($days > 0) {
                        $formattedDuration .= $days . ' days';
                    }
                    $premiumSubscription = $formattedDuration;
                }
            }

            $household->profile_img = $household->profile_img = isset($household->profile_img) ? asset('images/households'). '/' . $household->profile_img : null;

            $household['members'] = $this->householdRepository->getHouseholdMemberProfileData($this->householdRepository->getHouseholdMembers($household->id));

            return response()->json([
                'status' => true,
                'data' => $household,
                'is_premium' => $isPremium,
                'premium_subscription' => $premiumSubscription
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Error retrieving household. '. $e->getMessage()
            ], 500);
        }
    }

    public function getstates(){
        $states = State::all();
        return response()->json([
           'status' => true,
            'data' => $states
        ]);
    }

    public function getById($id){
        if($id){
            $household = Household::find($id);

            if(isset($household)){
                $members = $this->householdRepository->getHouseholdMemberProfileData($this->householdRepository->getHouseholdApprovedMembers($household->id));
                $is_admin = HouseholdUser::where(['household_id' => $household->id, 'user_id' => auth()->user()->id, 'is_admin' => 1, 'status' => 1])->exists();

                $householdUser = HouseholdUser::where(['user_id' => auth()->user()->id, 'status' => 1])->first();

                $join_request_sent = HouseholdUser::where(['user_id' => auth()->user()->id, 'household_id' => $household->id])->exists();
                $is_connected = false;
                $connection_request_status = null;

                if(isset($householdUser)){
                    $is_connected = HouseholdHousehold::where(['requested_household_id' => $householdUser->household_id, 'household_id' => $household->id, 'status' => 1])->orWhere(['requested_household_id' => $household->id, 'household_id' => $householdUser->household_id, 'status' => 1])->exists();
                }

                if(isset($householdUser)){
                    $connection_request_status = HouseholdHousehold::where(['requested_household_id' => $householdUser->household_id, 'household_id' => $household->id])->first();
                }
                
                $isPremium = false;
                $premiumSubscription = null;
                if (!is_null($household->premium_expiry)) {
                    $currentDateTime = Carbon::now();
                    $premiumExpiryDateTime = Carbon::parse($household->premium_expiry);
                
                    if ($premiumExpiryDateTime->gt($currentDateTime)) {
                        $isPremium = true;
                        // Calculate remaining duration in months and days
                        $remainingDuration = $premiumExpiryDateTime->diff($currentDateTime);
                        $months = $remainingDuration->m;
                        $days = $remainingDuration->d;
                        // Format remaining duration
                        $formattedDuration = '';
                        if ($months > 0) {
                            $formattedDuration .= $months . ' months';
                            if ($days > 0) {
                                $formattedDuration .= ' and ';
                            }
                        }
                        if ($days > 0) {
                            $formattedDuration .= $days . ' days';
                        }
                        $premiumSubscription = $formattedDuration;
                    }
                }

                $household->profile_img = $household->profile_img = isset($household->profile_img) ? asset('images/households'). '/' . $household->profile_img : null;

                $showlabels = HouseholdUser::where(['user_id' => auth()->user()->id,'household_id'=> $id ,'status' => 1])->exists();

                return response()->json([
                    'status' => true,
                    'data' => [
                        'household' => $household,
                        'members' => $members,
                        'is_admin' => $is_admin,
                        'show_labels' => $showlabels,
                        'join_request_sent' => $join_request_sent,
                        'is_connected' => $is_connected,
                        'connection_request_status' => $connection_request_status,
                        'is_premium' => $isPremium,
                        'premium_subscription' => $premiumSubscription
                    ]
                ]);

            }else{
                return response()->json([
                    'status' => false,
                    'error' => 'Household not found.'
                ], 404);
            }

        }else{
            return response()->json([
                'status' => false,
                'error' => 'Household Id is required.'
            ], 500);
        }
    }

    public function joinRequest(Request $request){
        try {
            $user = auth()->user();

            if(!$user->dob){
                return response()->json([
                    'status' => false,
                    'error' => 'Please fill your missing profile information first.',
                ], 422);
            }

            
            $validator = Validator::make($request->all(), [
                'household_id' => 'required|exists:households,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                ], 422);
            }

            $household = Household::findOrFail($request->household_id);

            if (!$household) {
                return response()->json([
                    'status' => false,
                    'error' => 'Household not found.'
                ], 404);
            }
            if($this->householdRepository->isHouseHoldUserPresent($user->id, 1)){
                return response()->json([
                    'status' => false,
                    'error' => 'You are already admin of a Household.'
                ], 404);
            }

        //    $alreadyJoined = HouseholdUser::where(['user_id' => $user->id, 'is_admin' => $is_admin])->exists();

            $data = [
                'user_id' => $user->id,
                'household_id' => $request->household_id,
                'is_admin' => 0,
                'status' => 0
            ];

            $householdAdmin = HouseholdUser::where(['household_id' => $request->household_id, 'is_admin' => 1, 'status' => 1])->first();

            if($this->householdRepository->isHouseHoldUserPresent($user->id, 0)){
                $isUpdated = HouseholdUser::where('user_id', $user->id)->update(['household_id' => $request->household_id, 'is_admin' => 0, 'status' => 0]);

                if($isUpdated) {
                    $this->notificationRepository->sendNotification($householdAdmin->user->fcm_token, 'New! Household Join Request', $user->username.' requested to join your household.');
                }
            }else{
                $createdhousehold = HouseholdUser::create($data);

                if($createdhousehold) {
                    $this->notificationRepository->sendNotification($householdAdmin->user->fcm_token, 'New! Household Join Request', $user->username.' requested to join your household.');
                }
            }


            if ($household->profile_img) {
                $household['profile_img'] = asset('images/households') . '/' . $household->profile_img;
            }

            return response()->json([
                'status' => true,
                'message' => 'Household join is requested by user',
                'household' => $household
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->errors(),
                'message' => 'Failed.'
            ], 422);
        }
    }

    public function getJoinRequests(){
        try {
            $user = auth()->user();

            // Check if the user is an admin of any household
            $isAdmin = HouseholdUser::where('is_admin', true)->where('user_id', $user->id)->get();
            if ($isAdmin->IsEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to view join requests.',
                ], 403);
            }

            // Fetch all households with join requests and their users
            $households = HouseholdUser::where('household_id', $isAdmin[0]->household_id)->where('is_admin', 0)->where('status', 0)->get();

            if ($households->IsEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No join requests found.',
                ], 404);
            } else {
                

                return response()->json([
                    'status' => true,
                    'message' => 'Join requests fetched successfully.',
                    'data' => $this->householdRepository->getHouseholdMemberData($households),
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

    public function updateJoinRequestStatus(Request $request)
    {
        try {
            $user = auth()->user();
            $householdUser = HouseholdUser::where('is_admin', true)->where('user_id', $user->id)->get();
            if (!$householdUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to update join request status',
                ], 403);
            }
            $validator = Validator::make($request->all(), [
                'request_id' => 'required|exists:household_user,id',
                'status' => 'required|in:Approved,Rejected',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Validation failed.',
                ], 422);
            }

            $statusMapping = [
                'Approved' => 1,
                'Rejected' => 2,
            ];

           $data = HouseholdUser::where('id', $request->request_id)
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

    public function adminJoinRequest(Request $request){
        try {
            $user = auth()->user();

            // Check if the user is an admin of any household
            $isAdmin = HouseholdUser::where('is_admin', true)->where('user_id', $user->id)->get();
            if ($isAdmin->IsEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to request for join.',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'household_id' => 'required|exists:households,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                ], 422);
            }
            if($isAdmin[0]->household_id == $request->household_id){
                return response()->json([
                    'status' => false,
                    'error' => "You can't request for your own household",
                ], 404);
            }

            $householdJoinRequest = HouseholdHousehold::where(['household_id' => $request->household_id, 'requested_household_id' => $isAdmin[0]->household_id])->exists();

            if($householdJoinRequest){
                return response()->json([
                    'status' => false,
                    'error' => "You already requested.",
                ], 500);
            }

            $requested_household = Household::find($isAdmin[0]->household_id);
            if (!$requested_household) {
                return response()->json([
                    'status' => false,
                    'error' => 'Requested Household not found.'
                ], 404);
            }

            $household = Household::find($request->household_id);
            if (!$household) {
                return response()->json([
                    'status' => false,
                    'error' => 'Household not found.'
                ], 404);
            }
            

            $data = [
                'household_id' => $request->household_id,
                'requested_household_id' => $isAdmin[0]->household_id,
                'status' => 0
            ];

            $householdRequest = HouseholdHousehold::create($data);

            if($householdRequest){
                $householdAdmin = HouseholdUser::where(['household_id' => $request->household_id, 'is_admin' => 1, 'status' => 1])->first();
                $this->notificationRepository->sendNotification($householdAdmin->user->fcm_token, 'New! Household Join Request', $requested_household->name .' household requested to connect with your household.');
            }

            return response()->json([
                'status' => true,
                'message' => 'Household join is requested'
                ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->errors(),
                'message' => 'Failed.'
            ], 422);
        }
    }

    public function getAdminJoinRequests(){
        try {
            $user = auth()->user();

            // Check if the user is an admin of any household
            $isAdmin = HouseholdUser::where('is_admin', true)->where('user_id', $user->id)->get();
            if ($isAdmin->IsEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to view join requests.',
                ], 403);
            }

            // Fetch all households with join requests and their users
            $households = HouseholdHousehold::where('household_id', $isAdmin[0]->household_id)->where('status', 0)->get(['requested_household_id', 'status']);

            // $households = HouseholdHousehold::selectRaw('household_id, GROUP_CONCAT(requested_household_id) as requested_household_ids, GROUP_CONCAT(status) as requested_household_statuses')->where('household_id', $isAdmin[0]->household_id)->groupBy('household_id')->get();

            if ($households->IsEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No join requests found.',
                ], 404);
            } else {
                $households->map(function ($household) {
                    $requestedHousehold = Household::find($household->requested_household_id);
                    if ($requestedHousehold->profile_img) {
                        $requestedHousehold->profile_img = asset('images/households') . '/' . $requestedHousehold->profile_img;
                    }

                    $household['household'] = $requestedHousehold;
                    $household['members'] = $this->householdRepository->getHouseholdMemberProfileData($this->householdRepository->getHouseholdMembers($household->requested_household_id));
    
                    return $household;
                });
                return response()->json([
                    'status' => true,
                    'message' => 'Join requests fetched successfully.',
                    'data' => $households
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

    public function updateAdminJoinRequestStatus(Request $request)
    {
        try {
            $user = auth()->user();
            $householdUser = HouseholdUser::where('is_admin', true)->where('user_id', $user->id)->first();
            if (!$householdUser->exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to update join request status',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'household_id' => [
                    'required',
                    Rule::exists('household_user', 'household_id')->where(function ($query) {
                        $query->where('is_admin', 1);
                    }),
                ],
                'status' => 'required|in:Approved,Rejected',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Validation failed.',
                ], 422);
            }

            $statusMapping = [
                'Approved' => 1,
                'Rejected' => 2,
            ];

            $data = HouseholdHousehold::where('household_id', $householdUser->household_id)
                    ->where('requested_household_id', $request->household_id)
                    ->update(['status' => $statusMapping[$request->status]]);
            
            if($data){
                return response()->json([
                    'status' => true,
                    'message' => 'Join request status updated successfully.',
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Join request status updated successfully.',
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update join request status.',
            ], 500);
        }
    }

    public function connectedHouseholds(){
        try {
            $user = auth()->user();
            if(!$this->householdRepository->isHouseHoldUserPresent($user->id, 1)){
                return response()->json([
                    'status' => false,
                    'error' => 'User has no assoication with Household.'
                ], 404);
            }

            $userHousehold = HouseholdUser::where('user_id', $user->id)->first();

            $connectedHouseholds = HouseholdHousehold::select('household_id')->where(['requested_household_id' => $userHousehold->household_id, 'status' => 1])->get();
            
            $connectedHouseholds->map(function ($connectedHousehold) {
                $connectedHousehold['household'] = Household::find($connectedHousehold->household_id);
                $connectedHousehold['members'] = $this->householdRepository->getHouseholdMemberProfileData($this->householdRepository->getHouseholdMembers($connectedHousehold->household_id));

                return $connectedHousehold;
            });
            
            return response()->json([
                'status' => true,
                'message' => 'Connected Households',
                'data' => $connectedHouseholds
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch.',
            ], 500);
        }
    }

    public function nonConnectedHouseholds(){
        try {
            $user = auth()->user();

            $userHousehold = HouseholdUser::where('user_id', $user->id)->first();

            $connectedHouseholds = [];
            if ($userHousehold->exists) {
                $connectedHouseholds = HouseholdHousehold::select('household_id')->where(['requested_household_id' => $userHousehold->household_id, 'status' => 1])->get()->toArray();
            }

            $nonConnectedHouseholds = Household::whereNotIn('id', $connectedHouseholds)->get();
            
            $nonConnectedHouseholds->map(function ($nonConnectedHousehold) {
                $nonConnectedHousehold['members'] = $this->householdRepository->getHouseholdMemberProfileData($this->householdRepository->getHouseholdMembers($nonConnectedHousehold->id));

                return $nonConnectedHousehold;
            });
            
            return response()->json([
                'status' => true,
                'message' => 'Non-Connected Households',
                'data' => $nonConnectedHouseholds
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch.',
            ], 500);
        }
    }

    public function getAgeApprovalJoinRequests()
    {
        try {
            $user = auth()->user();
            $isAdmin = HouseholdUser::where('is_admin', true)->where('user_id', $user->id)->get();
            if ($isAdmin->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to view Under Age join requests.',
                ], 403);
            }

            $userIds = HouseholdUser::where(['is_admin' => 0, 'household_id' => $isAdmin[0]->household_id])->pluck('user_id')->toArray();

            $households = GroupUser::whereIn('user_id', $userIds)->where('is_admin', 0)->where('status', 3)->get();

            if ($households->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No Under Age join requests found.',
                ], 404);
            } else {
                $households->map(function ($household){
                    $group = Group::findOrFail($household->group_id);
                    $group->profile_img = $group->profile_img = isset($group->profile_img) ? asset('images/groups'). '/' . $group->profile_img : null;
                    $household['group'] = $group;
                    $user = User::findOrFail($household->user_id);
                    $user->profile_img = $user->profile_img = isset($user->profile_img) ? asset('images/profile'). '/' . $user->profile_img : null;
                    $household['user'] = $user;
                    return $household;
                });

                return response()->json([
                    'status' => true,
                    'message' => 'Under Age Join requests fetched successfully.',
                    'data' => $households,
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
    public function updateUnderAgeJoinRequestStatus(Request $request)
    {
        try {
            $user = auth()->user();
            $householdUser = HouseholdUser::where('is_admin', true)->where('user_id', $user->id)->first();
    
            if (!$householdUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to update under age join request status',
                ], 403);
            }
    
            $validator = Validator::make($request->all(), [
                'request_id' => 'required|exists:group_user,id',
                'status' => 'required|in:Approved,Rejected',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Validation failed.',
                ], 422);
            }
    
            $statusMapping = [
                'Approved' => 0
            ];
    
            // Fetch the join request
            $joinRequest = GroupUser::find($request->request_id);
    
            if (!$joinRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'Under age join request not found.',
                ], 404);
            }
    
            if (($joinRequest->status == 3) && ($request->status == 'Approved')) {
                $joinRequest->update(['status' => $statusMapping[$request->status]]);
                
                return response()->json([
                    'status' => true,
                    'message' => 'Under age join request status updated successfully.',
                ], 200);
            } else if(($joinRequest->status == 3) && ($request->status == 'Rejected')){
                GroupUser::where('id', $request->request_id)->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Under age join request status updated successfully.',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid status for updating under age join request.',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update under age join request status.',
            ], 500);
        }
    }

    public function canPurchasePremium(Request $request){
        try {

            $user = auth()->user();
            $userid = $user->id;
    
            $householduser = HouseholdUser::where('user_id', $userid)
            ->where('status',1)->first();
            if (!$householduser) {
    
                return response()->json([
                    'status' => false,
                    'message' => 'Household not found.',
                ], 404); 
            }
    
            $household = Household::find($householduser->household_id);
    
            if (!$household) {
                return response()->json([
                    'status' => false,
                    'message' => 'Household not found.',
                ], 404);
            }
         
            $premiumExpiry = Carbon::parse($household->premium_expiry);

            if ($premiumExpiry->isAfter(Carbon::today())) {
                $remainingDays = $premiumExpiry->diffInDays(Carbon::today());
                
                return response()->json([
                    'status' => false,
                    'message' => 'Your previous subscription is not expired.',
                    'days' =>  $remainingDays
                ], 422);
            }

            return response()->json([
                'status' => true,
                'message' => 'You can renew your subscription',
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update premium expiry.',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
 public function updatePremiumExpiry(Request $request, $id)
    {
        try {

            $household = Household::find($id);
            if (!$household) {
                return response()->json([
                    'status' => false,
                    'message' => 'Household not found.',
                ], 404);
            }
            
            $userId = auth()->id();
            $isAdmin = HouseholdUser::where('user_id', $userId)
                ->where('household_id', $id)
                ->where('is_admin', 1)
                ->where('status', 1)
                ->exists();
            if (!$isAdmin) {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to update premium expiry for this household.',
                ], 403);
            }

            if (!$request->has('premium_expiry')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Premium expiry is required.',
                ], 422);
            }

            $premiumExpiry = Carbon::parse($request->input('premium_expiry'));

            if ($premiumExpiry->isBefore(Carbon::today())) {
                return response()->json([
                    'status' => false,
                    'message' => 'Premium expiry date must be today or later.',
                ], 422);
            }

            if ($premiumExpiry->diffInMonths(Carbon::today()) < 3) {
                return response()->json([
                    'status' => false,
                    'message' => 'Premium expiry date must be at least 3 months into the future.',
                ], 422);
            }

            $household = Household::find($id);
            $household->premium_expiry = $premiumExpiry;
            $household->save();

            return response()->json([
                'status' => true,
                'message' => 'Premium expiry updated successfully.',
                'data' => $household,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update premium expiry.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
   
}