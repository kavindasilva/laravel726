<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    //
    protected $table = 'bill_items';

    protected $fillable = ['item_id', 'bill_id', 'qty'];

    public $timestamps = false;
}
