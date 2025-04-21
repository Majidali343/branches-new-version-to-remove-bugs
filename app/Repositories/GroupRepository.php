<?php

namespace App\Repositories;

use App\Interfaces\GroupRepositoryInterface;
use App\Models\Group;
use App\Models\GroupHousehold;
use App\Models\GroupUser;
use App\Models\Household;

class GroupRepository implements GroupRepositoryInterface
{
    public function isGroup(int $group_id = 0)
    {
        return Group::where('id', $group_id)->exists();
    }

    public function isGroupUserPresent(int $user_id = 0, int $is_admin = 0)
    {
        return GroupUser::where(['user_id' => $user_id, 'is_admin' => $is_admin])->exists();
    }

    public function getGroupMemberData($groupUsers)
    {
        return $groupUsers->map(function ($group) {
            $statusNames = ['Pending', 'Approved', 'Rejected', 'Need Age Approval'];
            $group->status = $statusNames[$group->status];
            $group['user'] = $group->user;
            $group['user']->profile_img = isset($group['user']->profile_img) ? asset('images/profile') . '/' . $group['user']->profile_img : null;
            $group['group'] = $group->group;
            $group['group']->profile_img = isset($group['group']->profile_img) ? asset('images/groups') . '/' . $group['group']->profile_img : null;
            return $group;
        });
    }

    public function getGroupMemberProfileData($groupUsers)
    {
        return $groupUsers->map(function ($group) {
            $statusNames = ['Pending', 'Approved', 'Rejected'];
            $group->status = $statusNames[$group->status];
            $group['user'] = $group->user;
            $group['user']->profile_img = isset($group['user']->profile_img) ? asset('images/profile') . '/' . $group['user']->profile_img : null;
            if($group->user->userHousehold){
                $householdProfileImg = $group->user->userHousehold->household->profile_img;
                $group->user->userHousehold->household->profile_img = $householdProfileImg ? asset('images/households') . '/' . $householdProfileImg : null;
    
                $group['address'] = $group->user->userHousehold->household->address;
            }
            return $group;
        });
    }

    public function getGroupMembers($groupId)
    {
        return GroupUser::where('group_id', $groupId)->where('status', 1)->get();
    }

    public function getGroupApprovedMembers($groupId)
    {
        return GroupUser::where('group_id', $groupId)->where('status', 1)->get();
    }

    public function getGroupIdByAdminUserId(int $user_id = 0)
    {
        $group = GroupUser::select('group_id')->where(['user_id' => $user_id, 'is_admin' => 1, 'status' => 1])->first();
        return $group ? $group->group_id : null;
    }

    public function getApprovedMembersList(int $group_id = 0)
    {
        $groupHouseholds = GroupHousehold::where(['group_id' => $group_id, 'status'=>1])->get();

        foreach ($groupHouseholds as $groupHousehold) {
            $household = Household::find($groupHousehold->household_id);
            $household->profile_img = isset($household->profile_img) ? asset('images/households'). '/' . $household->profile_img : null;
            $groupHousehold['household'] = $household;
        }

        return $groupHouseholds;
    }
}
