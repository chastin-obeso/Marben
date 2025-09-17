<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOrder extends Model
{
    protected $table = 'job_orders';

    protected $fillable = [
        'job_order_number',
        'service_type',
        'description',
        'date_requested',
        'date_started',
        'date_targed',
        'date_finished',
        'status',
        'customer_id',
    ];
    public $timestamps = false;

    public function logs()
    {
        return $this->hasMany(JobOrderLog::class, 'job_order_id');
    }

    public function parts()
    {
        return $this->hasMany(JobOrderPart::class, 'job_order_id');
    }

    public function customer() 
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
