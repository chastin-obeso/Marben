<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class JobOrder extends Model
{
    protected $table = 'job_orders';

    protected $fillable = [
        'job_order_number',
        'description',
        'date_requested',
        'date_started',
        'date_target',
        'date_finished',
        'status',
        'customer_id',
        'user_id',
        'series',
        'service_type_id',
        're_job_order_id',
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
        return $this->belongsTo(Customer::class);
    }
    public function serviceType() 
    {
        return $this->belongsTo(ServiceType::class);
    }
    
    public function bills()
    {
        return $this->hasMany(\App\Models\Bill::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function reJobOrder()
    {
        return $this->belongsTo(JobOrder::class, 're_job_order_id');
    }

    public function scopeDateBetween(Builder $query, array $date)
    {
        $query->whereBetween('date_requested', [$date]);
    }
}
