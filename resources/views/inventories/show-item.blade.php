@extends('layouts.app')

@section('title', 'Item Details')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Item: {{ $item->name }}</h1>
    <div class="space-x-2">
        <a href="{{ route('inventory.items.edit', $item->id) }}" class="px-3 py-1 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700">Edit</a>
        <a href="{{ route('inventory.items') }}" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">Back</a>
    </div>
</div>
<div class="bg-white rounded shadow p-4">
    <p class="text-gray-500">Item details go here.</p>
</div>
@endsection
