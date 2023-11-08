<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TOPdeskAsset extends Model
{
    use HasFactory;

    protected $primaryKey = 'unid';
    public $incrementing = false;

    protected $connection = 'topdesk';

    public $timestamps = false;

    protected $table = 'am_entity';

    //protected $with = ['assetValues'];

    protected static function booted(): void
    {
        static::addGlobalScope('status_aktiv', function (Builder $builder) {
            $builder->join('am_value', 'am_entity.unid', '=', 'am_value.entityid')
                    ->where('am_value.fieldname', '=', 'status')
                    ->where('am_value.textvalue', '=', 'd7ab463b-4cbb-4fd8-98d7-7e3a495c5d08');
        });
    }

    public function persons(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\TOPdeskPerson', 'am_assignment', 'assetid', 'assignedentityid');
    }

    public function person()
    {
        return $this->belongsToMany('App\Models\TOPdeskPerson', 'am_assignment', 'assetid', 'assignedentityid')->first();
    }

    public function assetValues()
    {
        return $this->hasMany(TOPdeskAssetValue::class, 'entityid');
    }

    public function getRawStatusAttribute()
    {
        $asset_value = $this->assetValues->where('fieldname', 'status')->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getLeasingprisAttribute()
    {
        $asset_value = $this->assetValues->where('fieldname', 'leasingpris')->first();
        return $asset_value ? $asset_value->numvalue : null;
    }

    public function getBeskrivningAttribute()
    {
        $asset_value = $this->assetValues->where('fieldname', 'beskrivning')->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getArtikelnummerAttribute()
    {
        $asset_value = $this->assetValues->where('fieldname', 'artikelnummer')->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getUtbytesdatumAttribute()
    {
        $asset_value = $this->assetValues->where('fieldname', 'utbytesdatum')->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    protected $appends = ['leasingpris', 'beskrivning', 'artikelnummer'];
}
