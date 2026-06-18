@extends('layouts.app')

@section('title', 'Vehicle Details')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Vehicle: {{ $vehicle->vehicle_no }}</h1>
    <a href="{{ route('transport.vehicles') }}" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">Back</a>
</div>
<div class="bg-white rounded shadow p-4">
    <p class="text-gray-500">Vehicle details go here.</p>
</div>
@endsection
