<?php

namespace App\Repositories;

use App\Interfaces\Repositories\ManufacturerRepositoryInterface;
use App\Models\Manufacturer;
use Illuminate\Support\Collection;

class ManufacturerRepository implements ManufacturerRepositoryInterface
{
    public function getAll(): Collection
    {
        return Manufacturer::all();
    }
}
