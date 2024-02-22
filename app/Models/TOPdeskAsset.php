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
                    ->where('am_value.fieldid', '=', 61)
                    ->where('am_value.textvalue', '=', env("TD_LEASINGSERVICECAPABILITY"));
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

    public function leasingservice()
    {
        return $this->belongsToMany('App\Models\TOPdeskAsset', 'am_relation', 'targetid', 'sourceid')
                    ->wherePivot('capabilityId', '=', '0FDD0D35-B912-4A1D-9CF7-BE1623520F09')
                    ->withoutGlobalScope('status_aktiv')
                    ->first();
    }

    public function subassets()
    {
        return $this->belongsToMany('App\Models\TOPdeskAsset', 'am_relation', 'targetid', 'sourceid')
                    ->wherePivot('capabilityId', '=', '58f7683a-de2c-4ee6-be2d-484e8fa4cdaa');
    }

    public function customer()
    {
        return $this->belongsToMany('App\Models\TOPdeskCustomer', 'am_assignment', 'assetid', 'assignedentityid')->first();
    }

    public function assetValues()
    {
        return $this->hasMany(TOPdeskAssetValue::class, 'entityid');
    }

    public function getRawStatusAttribute()
    {
        $asset_value = $this->assetValues->where('fieldid', 61)->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getLeasingprisAttribute()
    {
        $asset_value = $this->assetValues->where('fieldid', 36)->first()?->numvalue;
        $subassets = $this->subassets;
        foreach($subassets as $subasset) {
            $asset_value += $subasset->leasingpris;
        }
        return $asset_value;
    }

    public function getBeskrivningAttribute()
    {
        $asset_value = $this->assetValues->where('fieldid', 22)->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getArtikelnummerAttribute()
    {
        $asset_value = $this->assetValues->where('fieldid', 21)->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getTypAttribute()
    {
        $asset_value = $this->assetValues->where('fieldid', 68)->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getZervicepointTjansteIdAttribute()
    {
        $asset_value = $this->assetValues->where('fieldid', 75)->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getShortnameAttribute()
    {
        $explodedname = explode(" ", $this->name);
        return $explodedname[1];
    }

    public function getPrettyShortnameAttribute()
    {
        return str_replace("_", " ", $this->shortname);
    }

    public function getValtUtbyteAttribute()
    {
        $asset_value = $this->assetValues->where('fieldid', 64)->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getOrdernummerUtbyteAttribute()
    {
        $asset_value = $this->assetValues->where('fieldid', 49)->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getUtbytesdatumAttribute()
    {
        $asset_value = $this->assetValues->where('fieldid', 63)->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getLeasingmanaderAttribute()
    {
        $asset_value = $this->leasingservice()?->assetValues->where('fieldid', 72)->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getSenastInloggadAttribute()
    {
        $asset_value = $this->assetValues->where('fieldid', 53)->first()?->textvalue;
        return TOPdeskPerson::find($asset_value)?->ref_dynanaam;
    }

    public function getSenastScannadAttribute()
    {
        $asset_value = $this->assetValues->where('fieldid', 54)->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    public function getAnteckningarAttribute()
    {
        $asset_value = $this->assetValues->where('fieldid', 20)->first();
        return $asset_value ? $asset_value->textvalue : null;
    }

    protected $appends = ['leasingpris', 'beskrivning', 'artikelnummer', 'anteckningar', 'utbytesdatum'];
}
