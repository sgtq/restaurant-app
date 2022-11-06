<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guest_number',
        'status_id',
        'location_id',
    ];

    public function status()
    {
        return $this->belongsTo(TableStatus::class);
    }

    public function location()
    {
        return $this->belongsTo(TableLocation::class);
    }
}
