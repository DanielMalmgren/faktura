<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TOPdeskPerson extends Model
{
    use HasFactory;
    use HasUuids;
    
    protected $primaryKey = 'unid';
    protected $keyType = 'string';

    protected $connection = 'topdesk';

    public $timestamps = false;

    protected $table = 'persoon';
}
