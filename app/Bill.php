<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $table = 'bill';

    protected $fillable = ['cashier', 'total'];
    
    protected $itemsa = [];

    /**
     * The items that belong to the bill.
     */
    public function items()
    {
        // return $this->belongsToMany('App\Item');
        return $this->belongsToMany(Item::class, 'bill_items', 'bill_id', 'item_id')->withPivot('qty');
    }

    public function extendedResult(){
        // $this->items = $this->items;
        $this->items;
        return $this;
    }
}
