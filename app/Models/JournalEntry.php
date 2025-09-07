<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'entry_date',
        'entry_number',
        'description',
        'reference_number',
        'total_amount'
    ];

    public function details()
    {
        return $this->hasMany(JournalEntryDetail::class);
    }
}
