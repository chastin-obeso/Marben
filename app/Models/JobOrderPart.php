<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOrderPart extends Model
{
    protected $table = 'job_order_parts';

    protected $fillable = [
        'name',
        'unit_price',
        'quantity',
        'total_price',
        'status',
        'job_order_id',
    ];

    
    public $timestamps = false;

    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function bill()
    {
        return $this->hasMany(Bill::class);
    }
}
