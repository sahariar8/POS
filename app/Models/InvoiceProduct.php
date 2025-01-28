<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    protected $fillable = ['sale_price','qty','user_id','product_id','invoice_id'];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
