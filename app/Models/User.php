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

        $this->username = $username;
        if(isset($aduser)) {
            $this->name = $aduser->displayName[0];
            //$this->customers = TOPdeskCustomer::where('email', 'like', '%'.$username.'%')->get();
            $this->customers = TOPdeskCustomer::orderBy('debiteurennummer')->get();
        }
    }

}
