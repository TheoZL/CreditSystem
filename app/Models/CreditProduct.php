<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditProduct extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'credit_id',
        'product_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'credit_id' => 'integer',
        'product_id' => 'integer',
    ];


    /**
     * Return a Product relation Model
     *
     * @return BelongsTo
     */
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id', 'id');
    }


    /**
     * Return a Credit relation Model
     *
     * @return BelongsTo
     */
    public function credit():BelongsTo
    {
        return $this->belongsTo(Credit::class,'credit_id', 'id');
    }
}
