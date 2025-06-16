<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These are fields you can fill in one go, like Order::create([...]).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'shipping_address',
        'payment_method',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
    ];

    /**
     * The attributes that should be cast to native types.
     * This ensures your data is always in the correct format.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount'   => 'decimal:2',
        'confirmed_at'   => 'datetime',
        'shipped_at'     => 'datetime',
        'delivered_at'   => 'datetime',
    ];

 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

   
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}