<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'datetime',
        'guest_number',
        'table_id',
    ];

    protected $dates = [
        'datetime'
    ];

    public function table() {
        return $this->belongsTo(Table::class);
    }
}
