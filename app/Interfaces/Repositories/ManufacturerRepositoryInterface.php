<?php

namespace App\Interfaces\Repositories;

use Illuminate\Support\Collection;

interface ManufacturerRepositoryInterface
{
    public function getAll(): Collection;
}
