<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransportController extends Controller
{
    public function routes()
    {
        $routes = DB::table('transport_routes')->orderBy('route_name')->paginate(20);
        return view('transport.routes', compact('routes'));
    }

    public function createRoute()
    {
        return view('transport.create-route');
    }

    public function storeRoute(Request $request)
    {
        $validated = $request->validate([
            'route_name' => 'required|string|max:100',
            'route_number' => 'nullable|string|max:50',
            'start_point' => 'nullable|string|max:255',
            'end_point' => 'nullable|string|max:255',
            'distance_km' => 'nullable|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        DB::table('transport_routes')->insert($validated + [
            'school_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->route('transport.routes')->with('success', 'Route created successfully');
    }

    public function showRoute(int $id)
    {
        $route = DB::table('transport_routes')->where('id', $id)->firstOrFail();
        return view('transport.show-route', compact('route'));
    }

    public function vehicles()
    {
        $vehicles = DB::table('transport_vehicles')->orderBy('vehicle_number')->paginate(20);
        return view('transport.vehicles', compact('vehicles'));
    }

    public function createVehicle()
    {
        return view('transport.create-vehicle');
    }

    public function storeVehicle(Request $request)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|max:50|unique:transport_vehicles,vehicle_number',
            'vehicle_type' => 'required|string|in:bus,van,car',
            'capacity' => 'required|integer|min:1',
            'vehicle_model' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:100',
            'year_of_manufacture' => 'nullable|integer|min:2000|max:2030',
            'insurance_expiry' => 'nullable|date',
            'status' => 'required|string|in:active,maintenance,retired',
        ]);

        DB::table('transport_vehicles')->insert($validated + [
            'school_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->route('transport.vehicles')->with('success', 'Vehicle added successfully');
    }

    public function showVehicle(int $id)
    {
        $vehicle = DB::table('transport_vehicles')->where('id', $id)->firstOrFail();
        return view('transport.show-vehicle', compact('vehicle'));
    }

    public function drivers()
    {
        $drivers = DB::table('transport_drivers')->orderBy('first_name')->paginate(20);
        return view('transport.drivers', compact('drivers'));
    }

    public function createDriver()
    {
        return view('transport.create-driver');
    }

    public function storeDriver(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:transport_drivers,email',
            'license_number' => 'required|string|max:50|unique:transport_drivers,license_number',
            'license_expiry' => 'required|date|after:today',
            'status' => 'required|string|in:active,inactive,suspended',
        ]);

        DB::table('transport_drivers')->insert($validated + [
            'school_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->route('transport.drivers')->with('success', 'Driver added successfully');
    }

    public function allocations()
    {
        $allocations = DB::table('transport_allocations')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('transport.allocations', compact('allocations'));
    }
}
