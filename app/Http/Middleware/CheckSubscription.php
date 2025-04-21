<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Household;
use App\Models\HouseholdUser;


class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
 
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

        if ($premiumExpiry->isBefore(Carbon::today())) {
            return response()->json([
                'status' => false,
                'message' => 'Subscription is ended.',
            ], 422);
        }

        return $next($request);
        
    }
}
