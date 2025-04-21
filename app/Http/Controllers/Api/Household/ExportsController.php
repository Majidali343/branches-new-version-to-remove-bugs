<?php

namespace App\Http\Controllers\Api\Household;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HouseholdExport;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\HouseholdUser;

class ExportsController extends Controller
{

    public function exportCsv(Request $request)
    {
        $user = auth()->user();
        $userid = $user->id;
        return Excel::download(new HouseholdExport($userid), 'households.csv');
    }

    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $userid = $user->id;

        $household = HouseholdUser::where('user_id', $userid)
        ->where('status',1)->first();

        if ($household) {

            $users = DB::table('household_user')
                ->join('users', 'household_user.user_id', '=', 'users.id')
                ->join('households', 'household_user.household_id', '=', 'households.id')
                ->select('households.household_id','users.fullname','users.username','users.email','users.phone', 'households.name', 'households.address','households.city', 'households.state', 'households.zip', 'households.country', )
                ->where('household_user.household_id', $household->household_id)
                ->get();

            $pdf = Pdf::loadView('pdf.Household', compact('users'));
            return $pdf->download('households.pdf');
        }

    }

}




