<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Builder;

class TOPdeskArticle extends Model
{
    use HasFactory;
    use HasUuids;
    
    protected $primaryKey = 'unid';
    protected $keyType = 'string';

    protected $connection = 'topdesk';

    public $timestamps = false;

    protected $table = 'hardware';

    protected static function booted(): void
    {
        static::addGlobalScope('har_objekttyp', function (Builder $builder) {
            $builder->where('ref_soort', '!=', '');
        });

        static::addGlobalScope('har_beskrivning', function (Builder $builder) {
            $builder->whereRaw("DATALENGTH(description) > 0");
        });
    }

    public function getShortnameAttribute()
    {
        $explodedname = explode(" ", $this->naam);
        return $explodedname[1];
    }

    public function getPrettyShortnameAttribute()
    {
        return str_replace("_", " ", $this->shortname);
    }

    public function relatedArticles(): HasMany
    {
        return $this->hasMany(TOPdeskArticle::class, 'ref_soort', 'ref_soort');
    }
}
