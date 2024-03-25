<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TOPdeskAssetView extends Model {
    use HasFactory;

    protected $connection = 'topdesk';

    public $timestamps = false;

    protected $table = 'assetportal';

    protected static function booted(): void {
        static::addGlobalScope('no_superassets', function (Builder $builder) {
            $builder->where('superassets', 0);
        });
    }
}
