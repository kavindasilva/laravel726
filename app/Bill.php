<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    /**
     * The items that belong to the bill.
     */
    public function items()
    {
        return $this->belongsToMany('App\Item');
    }
}
