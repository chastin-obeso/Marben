<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Bill extends Model
{
    use SoftDeletes;
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


    public function jobOrderParts()
    {
        return $this->hasMany(JobOrderPart::class);
    }

    public function serviceInvoices()
    {
        return $this->hasMany(ServiceInvoice::class);
    }

    public function updatePaymentStatus($refunded): void
    {
        $totalPaid = $this->serviceInvoices()->sum('amount_paid');
        $this->amount_due = max($this->total_amount - $totalPaid, 0);

        $newStatus = 'Unpaid';

        if ($refunded ?? true) {
            $newStatus = 'Refunded';
        } elseif ($this->amount_due <= 0) {
            $newStatus = 'Fully Paid';
        } elseif ($this->serviceInvoices()->exists()) {
            $newStatus = 'Partially Paid';
        }

        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
            $this->save();
        }
    }
        public $timestamps = false;
}
