@extends('layouts.app')

@section('title', 'Edit Item')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Edit Item</h1>
    <a href="{{ route('inventory.items') }}" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">Back</a>
</div>
<div class="bg-white rounded shadow p-4">
    <form method="POST" action="{{ route('inventory.items.update', $item->id) }}" class="space-y-4 max-w-lg">
        @csrf @method('PUT')
        <p class="text-gray-500">Edit item form fields go here.</p>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
    </form>
</div>
@endsection
