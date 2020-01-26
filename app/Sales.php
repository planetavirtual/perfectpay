<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = ['id', 'product_id', 'date', 'amount', 'customer_id', 'status', 'quantity'];
}
