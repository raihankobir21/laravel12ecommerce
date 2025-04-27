<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnProduct extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'order_id', 'product_id', 'quantity', 'reason', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Define the relationship to the customer through the order
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Define the relationship to the product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
