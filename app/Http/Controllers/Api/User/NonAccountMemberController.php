<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Interfaces\HouseholdRepositoryInterface;
use App\Models\Household;
use App\Models\HouseholdUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NonAccountMemberController extends Controller
{
    private HouseholdRepositoryInterface $householdRepository;

    public function __construct(HouseholdRepositoryInterface $householdRepository)
    {
        $this->householdRepository = $householdRepository;
    }

    public function add(Request $request){
        if(!$this->householdRepository->isHouseHoldUserPresent(auth()->user()->id, 1)){
            return response()->json([
                'status' => false,
                'error' => 'User has no admin assoication with Household.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users,username,' . auth()->id(),
            'fullname' => 'string',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dob' => 'nullable|date',
            'gender' => 'string',
            'country_code' => 'string',
            'address' => 'string',
            'phone' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('profile_img')) {
            $file = $request->file('profile_img');
            $fileName = $file->getClientOriginalName();
            $file->move('images/profile', $fileName);
            $data['profile_img'] = $fileName;
        }

        $data['non_account_member'] = 1;
        $user = User::create($data);

        if ($user) {
            if(isset($data['profile_img'])){
                $user['profile_img'] = asset('images/profile') . '/' . $data['profile_img'];
            }

            $household_id = $this->householdRepository->getHouseholdIdByAdminUserId(auth()->user()->id);

            if($household_id){
                HouseholdUser::create(['user_id' => $user->id, 'household_id' => $household_id, 'status' => 1, 'is_admin' => 0]);
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Non-account member created successfully',
                'user' => $user,
            ]);
        } else {
            return response()->json(['status' => false, 'error' => 'Failed to create non-account member'], 500);
        }
    }

    public function show(){

    }

    public function update(Request $request, $id){
        if(!$this->householdRepository->isHouseHoldUserPresent(auth()->user()->id, 1)){
            return response()->json([
                'status' => false,
                'error' => 'User has no admin assoication with Household.'
            ], 404);
        }
        $non_account_member = User::where(['id' => $id ])->first();
        if($non_account_member){
            $validator = Validator::make($request->all(), [
                'username' => 'string|unique:users,username,' . $non_account_member->id,
                'fullname' => 'string',
                'email' => 'email|unique:users,email,' . $non_account_member->id,
                'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'dob' => 'nullable|date',
                'gender' => 'string',
                'country_code' => 'string',
                'address' => 'string',
                'phone' => 'string'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => false, 'error' => $validator->errors()], 422);
            }
    
            $data = $validator->validated();
    
            if ($request->hasFile('profile_img')) {
                $oldImage = $non_account_member->profile_img;
                if ($oldImage && file_exists(public_path('images/profile/' . $oldImage))) {
                    unlink(public_path('images/profile/' . $oldImage));
                }
                $file = $request->file('profile_img');
                $fileName = $file->getClientOriginalName();
                $file->move('images/profile', $fileName);
                $data['profile_img'] = $fileName;
            }
    
            $non_account_member->update($data);
    
            if ($non_account_member) {
                if(isset($data['profile_img'])){
                    $non_account_member['profile_img'] = asset('images/profile') . '/' . $data['profile_img'];
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Non account member updated successfully',
                    'user' => $non_account_member,
                ]);
            } else {
                return response()->json(['status' => false, 'error' => 'Failed to update'], 500);
            }
        }else{
            return response()->json(['status' => false, 'error' => 'Non Account Member not found'], 500);
        }
    }

    public function delete($id){
        if(!$this->householdRepository->isHouseHoldUserPresent(auth()->user()->id, 1)){
            return response()->json([
                'status' => false,
                'error' => 'User has no admin assoication with Household.'
            ], 404);
        }

        $non_account_member_admin = HouseholdUser::where(['user_id' => $id, 'status' => 1, 'is_admin' => 1])->first();
       
        if( $non_account_member_admin ){
            return response()->json(['status' => false, 'message' => 'You are admin and can,t delete yourself'], 404);
        }

        $non_account_member_household = HouseholdUser::where(['user_id' => $id, 'status' => 1, 'is_admin' => 0])->first();
       
        if( !$non_account_member_household ){
            return response()->json(['status' => false, 'message' => 'not found'], 404);
        }
        $result = $non_account_member_household->delete();
        
        if($result){
            $non_account_member = User::where(['id' => $id ])->first();
            $result2 = $non_account_member->delete();
            if($result2){
                return response()->json([
                    'status' => true,
                    'message' => 'Non account member deleted successfully'
                ]);
            }else{
                return response()->json(['status' => false, 'error' => 'Failed to delete'], 500);
            }
        }else{
            return response()->json(['status' => false, 'error' => 'Failed to delete'], 500);
        }
    }
}