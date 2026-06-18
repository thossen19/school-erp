@extends('layouts.app')

@section('title', 'Inventory Items')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Inventory Items</h1>
    <a href="{{ route('inventory.items.create') }}" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Add Item</a>
</div>
<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th></tr></thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($items as $item)
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('inventory.items.show', $item->id) }}'">
                    <td class="px-4 py-2 text-sm">{{ $item->name }}</td>
                    <td class="px-4 py-2 text-sm">{{ $item->item_code }}</td>
                    <td class="px-4 py-2 text-sm {{ $item->stock_quantity <= $item->reorder_level ? 'text-red-600 font-semibold' : '' }}">{{ $item->stock_quantity }}</td>
                    <td class="px-4 py-2 text-sm">${{ number_format($item->unit_price, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-3 text-center text-gray-500">No items found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <x-pagination :paginator="$items" />
</div>
@endsection
