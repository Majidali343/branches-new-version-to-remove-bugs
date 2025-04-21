<?php

namespace App\Repositories;

use App\Interfaces\HouseholdRepositoryInterface;
use App\Models\Household;
use App\Models\HouseholdHousehold;
use App\Models\HouseholdUser;

class HouseholdRepository implements HouseholdRepositoryInterface
{
    public function isHouseHoldUserPresent(int $user_id = 0, int $is_admin = 0)
    {
       return HouseholdUser::where(['user_id' => $user_id, 'is_admin' => $is_admin])->exists();
    }

    public function getHouseholdMemberData($householdUsers){
        return $householdUsers->map(function ($household) {
            $statusNames = ['Pending', 'Approved', 'Rejected'];
            $household->status = $statusNames[$household->status];
            $household['user'] = $household->user;
            $household['user']->profile_img = isset($household['user']->profile_img) ? asset('images/profile'). '/' . $household['user']->profile_img : null;
            $household['household'] = $household->household;
            $household['household']->profile_img = isset($household['household']->profile_img) ? asset('images/households'). '/' . $household['household']->profile_img : null;
            return $household;
        });

    }

    public function getHouseholdMemberProfileData($householdUsers){
        return $householdUsers->map(function ($household) {
            $statusNames = ['Pending', 'Approved', 'Rejected'];
            $household->status = $statusNames[$household->status];
            $household['user'] = $household->user;
            $household['user']->profile_img = isset($household['user']->profile_img) ? asset('images/profile'). '/' . $household['user']->profile_img : null;
            return $household;
        });
    }

    public function getHouseholdMembers($householdId){
        return HouseholdUser::where('household_id', $householdId)->where('status', 1)->get();
    }

    public function getHouseholdApprovedMembers($householdId){
        return HouseholdUser::where('household_id', $householdId)->where('status', 1)->get();
    }

    public function getHouseholdHouseholdData($householdHouseholds){
        return $householdHouseholds->map(function ($householdHousehold) {
            $statusNames = ['Pending', 'Approved', 'Rejected'];

            $requestedHouseholdIds = explode(',', $householdHousehold->requested_household_ids);
            $requestedHouseholdStatuses = explode(',', $householdHousehold->requested_household_statuses);

            foreach($requestedHouseholdIds as $key => $requestedHouseholdId) {
                $houseHoldDetail = HouseholdUser::where('household_id', $requestedHouseholdId)->first();
                $houseHoldDetail['user'] = $houseHoldDetail->user;
                $houseHoldDetail['household'] = $houseHoldDetail->household;
                $houseHoldDetail['requested_household_status'] = $statusNames[$requestedHouseholdStatuses[$key]];
            }
            return $requestedHouseholdIds;
        });
    }

    public function getHouseholdIdByAdminUserId(int $user_id = 0){
        $household = HouseholdUser::select('household_id')->where(['user_id' => $user_id, 'is_admin' => 1, 'status' => 1])->first();
        return $household ? $household->household_id : null;
    }
}