<?php

namespace App\Models;

class User
{
    public $name;
    public $username;
    public $see_all; //Om kunden har behörighet att se samtliga kunder

    public $customers; //Kunder som användaren har rätt att beställa för
    public $customers_r; //Kunder som användaren kan se

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
                
                $kommuner = json_decode(env('MUNICIPALITIES'));
                foreach($kommuner as $kommun) {
                    if(isset($kommun->group) && strpos($kommun->group, "=") !== false) {
                        $group = \LdapRecord\Models\ActiveDirectory\Group::find($kommun->group);
                        if($aduser->groups()->recursive()->exists($group)) {
                            $this->customers_r = TOPdeskCustomer::where('debiteurennummer', 'like', $kommun->code.'%')
                                                                ->orderBy('debiteurennummer')
                                                                ->get();
                        }
                    }
                }
            }

            if($this->customers_r) {
                $this->customers_r = $this->customers->merge($this->customers_r);
            } else {
                $this->customers_r = $this->customers;
            }
        }
    }

}
