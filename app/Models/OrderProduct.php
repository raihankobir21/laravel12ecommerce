<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    // The pivot table automatically uses 'order_product', but you can customize it if needed.
    protected $table = 'order_product';

    // Fillable attributes for mass assignment in the pivot model
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];
}
