<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TOPdeskAssetValue extends Model
{
    use HasFactory;

    protected $connection = 'topdesk';

    public $timestamps = false;

    protected $table = 'am_value';
}
