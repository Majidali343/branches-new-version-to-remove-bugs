<?php

namespace App\Http\Controllers\Api\Membership;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Carbon\Carbon;

class MembershipController extends Controller
{
    public function membership(Request $request){
       
    $user = auth()->user();
    $now = Carbon::now();
    $subscription = Subscription::where('user_id', $user->id)->first();

    if($subscription){
        if ( $now->lessThan($subscription->ended_at)) {
            return response()->json([
                'message' => 'You subscription is not ended yet',
            ], 403);
        }

        $subscription->update([
            'plan' => 'membership',
            'status'=>'active',
            'started_at'=>Carbon::now(),
            'ended_at'=>Carbon::now()->addDays(30)
        ]);


        

        // return response()->json([
        //     'message' => 'You new subscription is started successfully',
        // ], 200);

    }


   


   

    }
}
