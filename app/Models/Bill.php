<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Bill extends Model
{
    /*
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'bill_number',
        'bill_series',
        'total_amount',
        'amount_due',
        'bill_date',
        'due_date',
        'particulars',
        'status',
        'job_order_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [

        ];
    }

    // Relationship section

    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function serviceInvoices()
    {
        return $this->hasMany(ServiceInvoice::class);
    }
}
