<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceType extends Model
{
    protected $table = 'service_types';

    protected $fillable = [
        'service'
    ];
    public $timestamps = false;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function job_orders(): HasMany
    {
        return $this->hasMany(JobOrder::class, 'service_type_id');
    }
}
