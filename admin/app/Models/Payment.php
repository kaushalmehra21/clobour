<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['bill_id', 'resident_id', 'amount', 'method', 'status', 'reference'];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'colony_id',
        'bill_id',
        'resident_id',
        'unit_id',
        'payment_number',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'payment_gateway',
        'gateway_response',
        'payment_date',
        'cheque_number',
        'cheque_date',
        'bank_name',
        'notes',
        'received_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'cheque_date' => 'date',
        'gateway_response' => 'array',
    ];

    // Relationships
    public function bill()
    {
        return $this->belongsTo(MonthlyBill::class, 'bill_id');
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
