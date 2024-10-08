<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentHistory extends Model
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
        'credit_id',
        'day',
        'amount',
        'type_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'credit_id' => 'integer',
        'day' => 'date',
        'amount' => 'float',
        'type_id' => 'integer',
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
     * Return a Credit relation Model
     *
     * @return BelongsTo
     */
    public function credit():BelongsTo
    {
        return $this->belongsTo(Credit::class,'credit_id','id');
    }

    /**
     * Return a Payment Type relation Model
     *
     * @return BelongsTo
     */
    public function paymentType():BelongsTo
    {
        return $this->belongsTo(PaymentType::class,'type_id','id');
    }

}
