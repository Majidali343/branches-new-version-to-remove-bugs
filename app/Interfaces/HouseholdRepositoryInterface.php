<?php

namespace App\Interfaces;

use App\Models\HouseholdUser;

interface HouseholdRepositoryInterface
{
    public function isHouseHoldUserPresent(int $user_id, int $is_admin);
    public function getHouseholdMemberData($householdUsers);
    public function getHouseholdMemberProfileData($householdUsers);
    public function getHouseholdMembers($household);
    public function getHouseholdApprovedMembers($household);
    public function getHouseholdHouseholdData($householdHouseholds);
    public function getHouseholdIdByAdminUserId(int $user_id);
}