@extends('layouts.app')

@section('title', 'Route Details')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Route: {{ $route->name }}</h1>
    <a href="{{ route('transport.routes') }}" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">Back</a>
</div>
<div class="bg-white rounded shadow p-4">
    <p class="text-gray-500">Route details go here.</p>
</div>
@endsection
