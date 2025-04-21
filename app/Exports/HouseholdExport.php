<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use App\Models\HouseholdUser;


class HouseholdExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $userid;

    public function __construct($userid)
    {
        $this->userid =$userid;
    }
   
    public function collection()
    {

        $household = HouseholdUser::where('user_id', $this->userid)
        ->where('status',1)->first();

        if ($household) {

            $users = DB::table('household_user')
            ->join('users', 'household_user.user_id', '=', 'users.id')
            ->join('households', 'household_user.household_id', '=', 'households.id')
            ->select('households.household_id','users.fullname','users.username','users.email','users.phone' ,'households.name', 'households.address','households.city', 'households.state', 'households.zip', 'households.country')
            ->where('household_user.household_id', $household->household_id)
            ->get();

            return $users;
        }
    }

    public function headings(): array
    {
        return [
            'Household ID',
            'Full Name',
            'User Name',
            'Email',
            'Phone',
            'Household name',
            'Address',
            'City',
            'State',
            'Zip',
            'Country',
        ];
    }
}
