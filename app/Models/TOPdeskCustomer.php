<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TOPdeskCustomer extends Model
{
    use HasFactory;
    use HasUuids;
    
    protected $primaryKey = 'unid';
    protected $keyType = 'string';
    //public $incrementing = false;

    protected $connection = 'topdesk';

    public $timestamps = false;

    protected $table = 'vestiging';

    protected static function booted(): void
    {
        static::addGlobalScope('har_kundnr', function (Builder $builder) {
            $builder->where('debiteurennummer', '!=', '');
        });
    }

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\TOPdeskAsset', 'am_assignment', 'assignedentityid', 'assetid');
    }
}
