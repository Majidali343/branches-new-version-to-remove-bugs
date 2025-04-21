<?php

namespace App\Interfaces;

use App\Models\GroupUser;

interface GroupRepositoryInterface
{
    public function isGroup(int $group_id);
    public function isGroupUserPresent(int $user_id, int $is_admin);
    public function getGroupMemberData($groupUsers);
    public function getGroupMemberProfileData($groupUsers);
    public function getGroupMembers($group);
    public function getGroupApprovedMembers($group);
    public function getGroupIdByAdminUserId(int $user_id);
    public function getApprovedMembersList(int $group_id);
}