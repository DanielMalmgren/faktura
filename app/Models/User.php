<?php

namespace App\Models;

class User
{
    public $name;
    public $username;
    public $see_all;

    public $customers;

    public function __construct(String $username)
    {
        $aduser = \LdapRecord\Models\ActiveDirectory\User::where('sAMAccountName', $username)->first();

        $admingroup = \LdapRecord\Models\ActiveDirectory\Group::find(env('ADMIN_GROUP'));

        $this->username = $username;
        if(isset($aduser)) {
            $this->name = $aduser->displayName[0];
            if($aduser->groups()->recursive()->exists($admingroup)) {
                $this->see_all = true;
                $this->customers = TOPdeskCustomer::where('surface_area_m2', '>', 0)
                                                    ->orderBy('debiteurennummer')
                                                    ->get();
            } else {
                $this->see_all = false;
                $this->customers = TOPdeskCustomer::where('email', 'like', '%'.$username.'%')
                                                    ->orderBy('debiteurennummer')
                                                    ->get();
            }
        }
    }

}
