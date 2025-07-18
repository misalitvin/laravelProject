<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrUpdateServiceRequest;
use App\Models\Service;

final class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(10);

        return view('admin.services.index', ['services' => $services]);
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(StoreOrUpdateServiceRequest $request)
    {
        Service::create($request->validated());

        return redirect()->route('admin.services.index');
    }

    public function update(StoreOrUpdateServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        return redirect()->route('admin.services.index');
    }

    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index');
    }
}
