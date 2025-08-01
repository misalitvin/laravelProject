<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrUpdateServiceRequest;
use App\Interfaces\Repositories\ServiceRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class ServiceController extends Controller
{
    public function __construct(
        protected ServiceRepositoryInterface $serviceRepository,
    ) {}

    public function index(): View
    {
        $services = $this->serviceRepository->paginate();

        return view('admin.services.index', ['services' => $services]);
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(StoreOrUpdateServiceRequest $request): RedirectResponse
    {
        $this->serviceRepository->create($request->validated());

        return redirect()->route('admin.services.index');
    }

    public function update(StoreOrUpdateServiceRequest $request, int $id): RedirectResponse
    {
        $service = $this->serviceRepository->findById($id);
        if ($service === null) {
            abort(404);
        }

        $this->serviceRepository->update($service, $request->validated());

        return redirect()->route('admin.services.index');
    }

    public function show(int $id): View
    {
        $service = $this->serviceRepository->findById($id);
        if ($service === null) {
            abort(404);
        }

        return view('admin.services.show', compact('service'));
    }

    public function edit(int $id): View
    {
        $service = $this->serviceRepository->findById($id);
        if ($service === null) {
            abort(404);
        }

        return view('admin.services.edit', compact('service'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $service = $this->serviceRepository->findById($id);
        if ($service === null) {
            abort(404);
        }

        $this->serviceRepository->delete($service);

        return redirect()->route('admin.services.index');
    }
}

