<?php

namespace App\Models;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Returns extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'return_date',
        'fine'
    ];

    public function loans() {
        return $this->belongsTo(Loan::class);
    }
}
