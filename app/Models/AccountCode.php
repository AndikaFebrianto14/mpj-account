<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'kelkode_id',
        'code_name',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(AccountCategory::class, 'kelkode_id');
    }
}
