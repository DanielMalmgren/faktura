<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TOPdeskAsset extends Model
{
    use HasFactory;

    protected $primaryKey = 'unid';
    public $incrementing = false;

    protected $connection = 'topdesk';

    public $timestamps = false;

    protected $table = 'am_entity';
}
