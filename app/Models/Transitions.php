<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transitions extends Model
{
    use HasFactory;
    protected $fillable = [
        "product_id",
        "repair_id",
        "type",
        "money_transition",
    ];
}
