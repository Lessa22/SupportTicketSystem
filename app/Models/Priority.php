<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $fillable = [
        'name',
        'color'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}