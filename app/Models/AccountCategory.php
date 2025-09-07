<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_code',
        'category_name',
        'category_type',
        'is_active'
    ];
}
