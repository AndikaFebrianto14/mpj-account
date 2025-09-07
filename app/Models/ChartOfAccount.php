<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_number',
        'account_name',
        'account_type',
        'code_id',
        'kelkode_id',
        'normal_balance',
        'parent_account_id',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(AccountCategory::class, 'kelkode_id');
    }

    public function code()
    {
        return $this->belongsTo(AccountCode::class, 'code_id');
    }

    public function parent()
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_account_id');
    }

    public function children()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_account_id');
    }

    public function details()
    {
        return $this->hasMany(\App\Models\JournalEntryDetail::class, 'account_id');
    }
}
