@extends('layouts.app')

@section('title', 'Transport Routes')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Transport Routes</h1>
    <a href="{{ route('transport.routes.create') }}" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Add Route</a>
</div>
<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stops</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicles</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th></tr></thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($routes as $route)
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('transport.routes.show', $route->id) }}'">
                    <td class="px-4 py-2 text-sm">{{ $route->name }}</td>
                    <td class="px-4 py-2 text-sm">{{ $route->stops_count ?? 0 }}</td>
                    <td class="px-4 py-2 text-sm">{{ $route->vehicles_count ?? 0 }}</td>
                    <td class="px-4 py-2 text-sm">{{ $route->status }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-3 text-center text-gray-500">No routes found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <x-pagination :paginator="$routes" />
</div>
@endsection
