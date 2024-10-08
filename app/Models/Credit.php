<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'date',
        'total',
        'first_payment',
        'balance',
        'route_id',
        'billing_schedule_id',
        'credit_status_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'customer_id' => 'integer',
        'date' => 'date',
        'first_payment' => 'float',
        'total' => 'float',
        'balance' => 'float',
        'route_id' => 'integer',
        'billing_schedule_id' => 'integer',
        'credit_status_id' => 'integer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Return a Customer relation Model
     *
     * @return BelongsTo
     */
    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    /**
     * Return a Customer relation Model
     *
     * @return BelongsTo
     */
    public function route():BelongsTo
    {
        return $this->belongsTo(Route::class,'route_id','id');
    }

    /**
     * Return a Billing Schedule relation Model
     *
     * @return BelongsTo
     */
    public function billingSchedule():BelongsTo
    {
        return $this->belongsTo(BillingSchedule::class,'billing_schedule_id','id');
    }

    /**
     * Return a Credit Status relation Model
     *
     * @return BelongsTo
     */
    public function creditStatus():BelongsTo
    {
        return $this->belongsTo(CreditStatus::class,'credit_status_id','id');
    }

    public function product()
    {
        return $this->BelongsToMany(Product::class)->using(CreditProduct::class);
    }


    public function buttonPaymentHistory()
    {
        return '<a class="btn btn-sm btn-link" href=" '. route('payment.history', $this->id) .' "  data-toggle="tooltip" title="Ver Historial"><i class="la la-book"></i> Ver Historial</a>';
    }

    public function buttonApplyPayment()
    {

        return $this->balance > 0
            ? '<a class="btn btn-sm btn-link" href="javascript:void(0)" onclick="applyPayment('.$this->id.','. $this->balance.')" title="Aplicar un pago"><i class="la la-money"></i> Pagar</a>'
            : '' ;
    }

}
