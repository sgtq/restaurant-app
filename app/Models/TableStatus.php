<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function tables()
    {
        return $this->hasMany(Table::class);
    }
}
