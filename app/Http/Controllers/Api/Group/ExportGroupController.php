<?php

namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GroupExport;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Group;

class ExportGroupController extends Controller
{

    public function exportCsv(Request $request,$id)
    {
        return Excel::download(new GroupExport($id), 'Group.csv');
    }

    public function exportPdf(Request $request,$id)
    {

        $Group = Group::where('id', $id)->first();

        if ($Group) {

            $user = auth()->user();
            $userid = $user->id;

            // $users_household = DB::table('household_user')
            // ->join('users', 'household_user.user_id', '=', 'users.id')
            // ->join('households', 'household_user.household_id', '=', 'households.id')
            // ->select('households.household_id','households.name','households.household_bio','households.address','households.city', 'households.state', 'households.zip', 'households.country',)
            // ->where('household_user.user_id',  $userid)
            // ->get();

            $users_groups = DB::table('group_household')
            ->join('households', 'group_household.household_id', '=', 'households.id')
            ->select('households.household_id','households.name','households.household_bio','households.address','households.city', 'households.state', 'households.zip', 'households.country', )
            ->where([
                ['group_household.group_id', $id],
                ['group_household.status', 1]
            ])
            ->get();

            // $users = $users_household->merge($users_groups);
            $users = $users_groups;

            $pdf = Pdf::loadView('pdf.Group', compact('users'));
            return $pdf->download('groups.pdf');
        }

    }

}




