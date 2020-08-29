<?php

namespace App;
/**
 * created using
 * php artisan make:model Item -m
 */

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $table = 'item';

    protected $fillable = ['name', 'batch', 'price'];
    
    // protected $items = [];

    public function bills()
    {
        // return $this->belongsToMany('App\Item');
        return $this->belongsToMany(Bill::class, 'bill_items', 'item_id', 'bill_id')->withPivot('qty');
        // return $this->belongsToMany(Bill::class, 'bill_items', 'bill_id', 'item_id');
    }

    public function extendedResult(){
        $this->items = [];
        // foreach ($this->items() as $item) {
        //     $this->items[] = $item;
        // }
    }
}
