<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'address'];

    /**
     * Get all the orders placed by the customer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the total amount spent by the customer on all orders.
     */
    public function totalSpent()
    {
        return $this->orders->sum('total_amount');  // Summing up total_amount from each order
    }

    /**
     * Get the last order placed by the customer.
     */
    public function lastOrder()
    {
        return $this->orders()->latest()->first();
    }

    /**
     * Get the number of orders placed by the customer.
     */
    public function orderCount()
    {
        return $this->orders()->count();
    }
}
