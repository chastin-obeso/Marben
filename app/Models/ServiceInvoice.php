<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ServiceInvoice extends Model
{
    /*
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'service_invoice_number',
        'service_invoice_series',
        'amount_paid',
        'payment_type',
        'payment_date',
        'bill_id'
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

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
