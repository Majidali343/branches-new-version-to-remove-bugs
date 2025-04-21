<?php
/* Helper Function 
 
1 groupIndividualRequestCount();
2 groupHouseHoldRequestCount()
2 statusMaping()

 */

use App\Models\GroupHousehold;
use App\Models\GroupUser;
use Illuminate\Support\Facades\DB;

function groupIndividualRequestCount($userGroups){
    $statusMapping = [
        0 => 'Pending',
        1 => 'Approved',
        2 => 'Rejected',
        3 => 'Age Approval'
    ];
    $statusCounts = [];
    foreach ($userGroups as $group_id) {
        $group = GroupUser::where('group_id', $group_id)->first();
        if ($group->is_admin) {
            $statusCounts = DB::table('group_user')
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();
        }
    }
    $userGroupsCounts = [];
    foreach ($statusCounts as $status => $count) {
        $statusLabel = isset($statusMapping[$status]) ? $statusMapping[$status] : 'Unknown';
        $userGroupsCounts[$statusLabel] = $count;
    }
    return $userGroupsCounts;
}
function groupHouseHoldRequestCount($userGroups){
    $statusMapping = [
        0 => 'Pending',
        1 => 'Approved',
        2 => 'Rejected',
    ];
    $statusCounts = [];
    foreach ($userGroups as $group_id) {
        $group = GroupHousehold::where('group_id', $group_id)->first();
        if ($group->is_admin) {
            $statusCounts = DB::table('group_household')
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();
        }
    }
    $userGroupsCounts = [];
    foreach ($statusCounts as $status => $count) {
        $statusLabel = isset($statusMapping[$status]) ? $statusMapping[$status] : 'Unknown';
        $userGroupsCounts[$statusLabel] = $count;
    }
    return $userGroupsCounts;
}

function statusMaping($collection, $statusMapping) {
    return $collection->map(function ($item) use ($statusMapping) {
        $item->status = $statusMapping[$item->status] ?? 'Unknown';
        return $item;
    });
}