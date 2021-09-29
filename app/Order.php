<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $guarded = [];

    public function cart(){
        return $this->belongsToMany(Product::class, 'order_details','order_id', 'product_id')
        ->withPivot('quantity', 'price', 'options', 'subtotal', 'note');
    }
}
