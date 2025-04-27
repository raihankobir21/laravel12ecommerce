<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;


    protected $fillable = ['customer_id', 'total_amount', 'status', 'shipping_address'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
                    ->withPivot('price', 'quantity')
                    ->withTimestamps();
    }


    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
    /**
     * Get the customer that owns the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the products associated with the order.
     */


    /**
     * Calculate the total amount for the order based on products' price and quantity.
     */


    /**
     * Update the status of the order.
     */
    public function updateStatus($status)
    {
        $this->status = $status;
        $this->save();
    }
}
