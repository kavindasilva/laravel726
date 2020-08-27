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
}
