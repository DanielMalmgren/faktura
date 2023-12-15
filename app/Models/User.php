<?php

namespace App\Models;

class User
{
    public $name;
    public $username;

    public $customers;

    public function __construct(String $username)
    {
        $aduser = \LdapRecord\Models\ActiveDirectory\User::where('sAMAccountName', $username)->first();

        $admingroup = \LdapRecord\Models\ActiveDirectory\Group::find(env('ADMIN_GROUP'));

        $this->username = $username;
        if(isset($aduser)) {
            $this->name = $aduser->displayName[0];
            if($aduser->groups()->recursive()->exists($admingroup)) {
                $this->customers = TOPdeskCustomer::where('surface_area_m2', '>', 0)
                                                    ->orderBy('debiteurennummer')
                                                    ->get();
            } else {
                $this->customers = TOPdeskCustomer::where('email', 'like', '%'.$username.'%')
                                                    ->orderBy('debiteurennummer')
                                                    ->get();
            }
        }
    }

}
