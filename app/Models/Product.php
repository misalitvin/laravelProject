<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'release_date', 'price', 'manufacturer_id'];

    public function services()
    {
        return $this->belongsToMany(Service::class)
            ->withPivot('days_to_complete', 'cost')
            ->withTimestamps();
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }
}
