<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Shipping extends Model
{
    public $fillable=['type','price','status'];
}