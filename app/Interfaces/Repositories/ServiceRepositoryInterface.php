<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ServiceRepositoryInterface
{
    public function getAll(): Collection;

    public function paginate(): LengthAwarePaginator;

    public function findById(int $id): ?Service;

    public function create(array $data): Service;

    public function update(Service $service, array $data): bool;

    public function delete(Service $service): bool;
}
