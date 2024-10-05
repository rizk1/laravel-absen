<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift',
        'mulai',
        'selesai'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'shift_id');
    }
}
