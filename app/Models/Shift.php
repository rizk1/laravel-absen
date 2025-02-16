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
        'selesai',
        'jabatan_id'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'shift_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
