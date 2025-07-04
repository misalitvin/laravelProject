<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('days_to_complete', 'cost')
            ->withTimestamps();
    }
    protected $fillable = ['name'];
}
