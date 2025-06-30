<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public function services()
    {
        return $this->belongsToMany(Service::class)
            ->withPivot('days_to_complete', 'cost')
            ->withTimestamps();
    }
    protected $fillable = ['name', 'description', 'manufacturer', 'release_date', 'price'];
}
