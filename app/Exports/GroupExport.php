<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use App\Models\Group;


class GroupExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $id;

    public function __construct($id)
    {
        $this->id =$id;
    }
   
    public function collection()
    {
     $Group = Group::where('id', $this->id)->first();

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
                ['group_household.group_id', $this->id],
                ['group_household.status', 1]
            ])
            ->get();

            // $users = $users_household->merge($users_groups);
            $users = $users_groups;


            return $users;
        }
    }

    public function headings(): array
    {
        return [
            'Household ID',
            'Full Name',
            'Address',
            'City',
            'State',
            'Zip',
            'Country',
        ];
    }
}
