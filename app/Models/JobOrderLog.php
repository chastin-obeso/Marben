<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOrderLog extends Model
{
    protected $table = 'job_order_logs';

    protected $fillable = [
        'details',
        'date',
        'job_order_id',
    ];

    public $timestamps = false;
}
