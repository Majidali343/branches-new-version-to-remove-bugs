<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Household\HouseholdController;
use App\Http\Controllers\Api\Household\ExportsController;
use App\Http\Controllers\Api\Group\GroupController;
use App\Http\Controllers\Api\Group\ExportGroupController;
use App\Http\Controllers\Api\Event\EventController;
use App\Http\Controllers\Api\User\NonAccountMemberController;
use App\Http\Controllers\Api\Task\TaskController;
use App\Http\Controllers\Api\Membership\MembershipController;


Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware('api');
Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('api');
Route::get('household/states', [HouseholdController::class, 'getstates'])->withoutMiddleware('api');


Route::group(['middleware' => ['api', 'subscription']], function () {

    //Groups

    //  Route::get('group/your', [GroupController::class, 'getyour']); // groups with you
    Route::post('group/create', [GroupController::class, 'store']);
    Route::post('group/update', [GroupController::class, 'update']);
   

    Route::post('group/household-admin-individual-request-/disapprove', [GroupController::class, 'householdAdmindisapprove']);
    Route::post('group/household-admin-individual-request-/approve', [GroupController::class, 'householdAdminApprove']);
    Route::get('group/individual-member-join-request/approve', [GroupController::class, 'requestForApproval']);
    Route::get('group/individual-member-join-request/all', [GroupController::class, 'getIndividualMemberJoinRequests']);
    Route::post('group/individual-member-join-request/update', [GroupController::class, 'updateIndividualMemberJoinRequestStatus']);
    Route::get('groups/individual-member/all', [GroupController::class, 'getIndividualMemberGroupsList']);
    Route::get('group/approved-individual-members/all', [GroupController::class, 'getApprovedGroupMembers']);
    Route::post('group/individuals/users/admin', [GroupController::class, 'updateIndividualMemberGroupAdmin']);
    Route::post('group/household-join-request/update', [GroupController::class, 'updateHouseholdJoinRequestStatus']);
    Route::get('group/households/all', [GroupController::class, 'getGroupHouseholdsList']);
    Route::get('group/household-members/all', [GroupController::class, 'getApprovedHouseholdMembers']);
    Route::get('group/joined', [GroupController::class, 'getUserJoinedGroups']);
    Route::get('group/not-joined', [GroupController::class, 'getUserNotJoinedGroups']);
  


    //Events
    Route::get('group/events/all', [EventController::class, 'getEventsByGroup']);
    Route::get('events/get', [EventController::class, 'getEvents']);
    Route::post('event/create', [EventController::class, 'addEvent']);
    Route::post('event/update', [EventController::class, 'updateEvent']);
    Route::delete('event/delete', [EventController::class, 'deleteEvent']);
   
    //Tasks
    Route::post('task/add', [TaskController::class, 'add']);
    Route::post('task/add-unassigned', [TaskController::class, 'add_unassigned']);
    Route::put('task/{Id}', [TaskController::class, 'update']);
    Route::put('assign-task/{Id}', [TaskController::class, 'assign_task']);
    Route::delete('task/delete', [TaskController::class, 'delete']);
});



Route::group(['middleware' => 'api'], function ($routes) {

    Route::post('logout', [AuthController::class, 'logout']);

    //Groups
    Route::get('group/get', [GroupController::class, 'get']); // groups except you
    Route::Post('/user/membership', [MembershipController::class, 'membership']);
    Route::get('group/household-join-request/all', [GroupController::class, 'getHouseholdJoinRequests']);
    Route::post('group/household-join-request', [GroupController::class, 'joinRequestByHousehold']);
    Route::get('group/view/{id}', [GroupController::class, 'show']);
    Route::post('group/individual-member-join-request', [GroupController::class, 'joinRequestByIndividualMember']);

    //User
    Route::post('/user/update', [UserController::class, 'update']);
    Route::get('/user/get', [UserController::class, 'getuser']);
    Route::get('/user/getprofile/{Id}', [UserController::class, 'userdetail']);

    //// events
    Route::get('event/view/{id}', [EventController::class, 'show']);
    Route::post('event/attendee/add', [EventController::class, 'addEventAttendee']);


    ///  all user statics 
    Route::get('/user/view', [UserController::class, 'show']);

    Route::get('singleuser/view/{Id}', [UserController::class, 'getsingleUserById']);
    Route::get('user/view/{Id}', [UserController::class, 'getUserById']);
    Route::get('user/events/all', [EventController::class, 'getAllAttendingEvents']);
    Route::get('/user/tasks/all', [TaskController::class, 'getSpecificUserTasks']);


    /// for exports pdf and csv files for groups
    Route::get('/group-csv/{id}', [ExportGroupController::class, 'exportCsv']);
    Route::get('/group-pdf/{id}', [ExportGroupController::class, 'exportPdf']);


    /// for exports pdf and csv files for groups
    Route::get('/export-csv', [ExportsController::class, 'exportCsv']);
    Route::get('/export-pdf', [ExportsController::class, 'exportPdf']);


    //Non Account Member
    Route::post('/non-account-member/add', [NonAccountMemberController::class, 'add']);
    Route::post('/non-account-member/update/{id}', [NonAccountMemberController::class, 'update']);
    Route::delete('/non-account-member/delete/{id}', [NonAccountMemberController::class, 'delete']);


    // Households
  
    Route::get('household/adminleave', [Householdcontroller::class, 'leave']);
    Route::post('household/create', [HouseholdController::class, 'store']);
    Route::get('household/get', [HouseholdController::class, 'get']);
    Route::post('household/update', [HouseholdController::class, 'update']);
    Route::get('household/view', [HouseholdController::class, 'show']);
    Route::get('household/get/{id}', [HouseholdController::class, 'getById']);
    Route::post('household/join-request', [HouseholdController::class, 'joinRequest']);
    Route::get('household/join-request/all', [HouseholdController::class, 'getJoinRequests']);
    Route::post('household/join-request/update', [HouseholdController::class, 'updateJoinRequestStatus']);
    Route::post('household/admin-join-request', [HouseholdController::class, 'adminJoinRequest']);
    Route::get('household/admin-join-request/all', [HouseholdController::class, 'getAdminJoinRequests']);
    Route::post('household/admin-join-request/update', [HouseholdController::class, 'updateAdminJoinRequestStatus']);
    Route::get('household/connected', [HouseholdController::class, 'connectedHouseholds']);
    Route::get('household/non-connected', [HouseholdController::class, 'nonConnectedHouseholds']);
    Route::get('household/under-age-join-request/all', [HouseholdController::class, 'getAgeApprovalJoinRequests']);
    Route::post('household/under-age-join-request/update', [HouseholdController::class, 'updateUnderAgeJoinRequestStatus']);
    Route::put('household/premium-subsription-update/{id}', [HouseholdController::class, 'updatePremiumExpiry']);
    Route::get('household/can-purchase-premium', [HouseholdController::class, 'canPurchasePremium']);

});

