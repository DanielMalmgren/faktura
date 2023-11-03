<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TOPdeskCustomer extends Model
{
    use HasFactory;

    protected $connection = 'topdesk';

    public $timestamps = false;

    protected $table = 'vestiging';

    protected static function booted(): void
    {
        static::addGlobalScope('har_kundnr', function (Builder $builder) {
            $builder->where('debiteurennummer', '!=', '');
        });
    }
}
